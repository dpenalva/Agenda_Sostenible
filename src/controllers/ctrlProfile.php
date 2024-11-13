<?php

function checkUploadDirectories() {
    $directories = [
        __DIR__ . '/../../public/uploads',
        __DIR__ . '/../../public/uploads/images',
        __DIR__ . '/../../public/uploads/profile_images'
    ];
    
    foreach ($directories as $dir) {
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
            error_log("Creado directorio: $dir");
        }
        
        if (!is_writable($dir)) {
            error_log("ADVERTENCIA: El directorio $dir no tiene permisos de escritura");
        }
    }
}

function ctrlProfile($request, $response, $container) {
    checkUploadDirectories();

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id'])) {
        header("Location: /?r=login");
        exit();
    }

    try {
        $usuarisModel = new \Models\UsuarisPDO($container->config['db']);
        $userData = $usuarisModel->getUserById($_SESSION['user_id']);
        
        error_log("Datos del usuario: " . print_r($userData, true));
        error_log("URL de imagen de perfil: " . ($userData['imatge_perfil'] ?? 'no hay imagen'));
        
        $response->set("userData", $userData);
        $response->setTemplate("profile.php");
        return $response;

    } catch (\Exception $e) {
        error_log("Error en perfil: " . $e->getMessage());
        $response->set("error", $e->getMessage());
        $response->setTemplate("error.php");
        return $response;
    }
}

function ctrlUpdateProfileImage($request, $response, $container) {
    try {
        $file = $_FILES['profileImage'] ?? null;
        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            throw new \Exception("Error al subir el archivo");
        }

        // Validaciones básicas
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($file['type'], $allowedTypes)) {
            throw new \Exception("Tipo de archivo no permitido");
        }

        $userId = $_SESSION['user_id'];
        $uploadDir = __DIR__ . '/../../public/uploads/profile_images/';
        
        // Asegurarse de que el directorio existe
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Generar nombre de archivo único
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = 'profile_' . $userId . '_' . uniqid() . '.' . $extension;
        $filePath = $uploadDir . $fileName;
        
        // Guardar archivo
        if (!move_uploaded_file($file['tmp_name'], $filePath)) {
            error_log("Error moviendo archivo a: $filePath");
            throw new \Exception("Error al guardar la imagen");
        }

        // URL relativa para la base de datos
        $imageUrl = '/uploads/profile_images/' . $fileName;
        
        // Actualizar en base de datos
        $usuarisModel = new \Models\UsuarisPDO($container->config['db']);
        $usuarisModel->updateProfileImage($userId, $imageUrl);
        
        error_log("Imagen actualizada correctamente: $imageUrl");

        $response->setJson();
        $response->set("success", true);
        $response->set("imageUrl", $imageUrl);
        return $response;

    } catch (\Exception $e) {
        error_log("Error actualizando imagen de perfil: " . $e->getMessage());
        $response->setJson();
        $response->set("success", false);
        $response->set("message", $e->getMessage());
        return $response;
    }
}

function ctrlUpdateBanner($request, $response, $container) {
    try {
        if (!isset($_FILES['bannerImage'])) {
            throw new \Exception("No se ha enviado ninguna imagen");
        }

        $file = $_FILES['bannerImage'];
        $userId = $_SESSION['user_id'];
        
        // Validar el archivo
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file['type'], $allowedTypes)) {
            throw new \Exception("Tipo de archivo no permitido");
        }
        
        // Limitar tamaño (5MB)
        if ($file['size'] > 5 * 1024 * 1024) {
            throw new \Exception("La imagen es demasiado grande");
        }

        $uploadDir = __DIR__ . '/../../public/uploads/banners/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = uniqid('banner_') . '.' . $extension;
        $filePath = $uploadDir . $fileName;

        if (!move_uploaded_file($file['tmp_name'], $filePath)) {
            throw new \Exception("Error al guardar la imagen");
        }

        $usuarisModel = new \Models\UsuarisPDO($container->config['db']);
        $imageUrl = '/uploads/banners/' . $fileName;
        $usuarisModel->updateBanner($userId, $imageUrl);

        $response->setJson();
        $response->set("success", true);
        $response->set("imageUrl", $imageUrl);
        return $response;

    } catch (\Exception $e) {
        $response->setJson();
        $response->set("success", false);
        $response->set("message", $e->getMessage());
        return $response;
    }
}

function ctrlUpdateProfile($request, $response, $container) {
    try {
        $data = json_decode(file_get_contents('php://input'), true);
        $userId = $_SESSION['user_id'];

        if (!isset($data['name']) || empty($data['name'])) {
            throw new \Exception("El nombre es requerido");
        }

        // Limitar la longitud de la biografía
        if (isset($data['bio']) && strlen($data['bio']) > 160) {
            throw new \Exception("La biografía no puede exceder los 160 caracteres");
        }

        $usuarisModel = new \Models\UsuarisPDO($container->config['db']);
        $usuarisModel->updateProfile($userId, $data);

        $response->setJson();
        $response->set("success", true);
        return $response;

    } catch (\Exception $e) {
        $response->setJson();
        $response->set("success", false);
        $response->set("message", $e->getMessage());
        return $response;
    }
} 