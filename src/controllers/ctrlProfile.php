<?php

function ctrlProfile($request, $response, $container) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id'])) {
        header("Location: /?r=login");
        exit();
    }

    try {
        $usuarisModel = new \Models\UsuarisPDO($container->config['db']);
        $user = $usuarisModel->get($_SESSION['user_id']);
        
        if (!$user) {
            throw new \Exception("Usuario no encontrado");
        }

        $response->set("userData", $user);
        $response->setTemplate("profile.php");
        return $response;

    } catch (\Exception $e) {
        $response->set("error", $e->getMessage());
        $response->setTemplate("error.php");
        return $response;
    }
}

function ctrlUpdateProfileImage($request, $response, $container) {
    try {
        if (!isset($_FILES['profileImage']) || $_FILES['profileImage']['error'] !== UPLOAD_ERR_OK) {
            throw new \Exception("Error al subir el archivo");
        }

        $file = $_FILES['profileImage'];
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

        // Crear directorio si no existe
        $uploadDir = __DIR__ . '/../../public/uploads/profile_images/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Generar nombre único
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = uniqid('profile_') . '.' . $extension;
        $filePath = $uploadDir . $fileName;

        // Mover archivo
        if (!move_uploaded_file($file['tmp_name'], $filePath)) {
            throw new \Exception("Error al mover el archivo subido");
        }

        // Actualizar en base de datos
        $usuarisModel = new \Models\UsuarisPDO($container->config['db']);
        $imageUrl = '/uploads/profile_images/' . $fileName;
        $usuarisModel->updateProfileImage($userId, $imageUrl);

        $response->setJson();
        $response->set("success", true);
        $response->set("imageUrl", $imageUrl);
        return $response;

    } catch (\Exception $e) {
        error_log("Error en actualización de imagen: " . $e->getMessage());
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