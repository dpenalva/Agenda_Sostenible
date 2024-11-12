<?php

function ctrlAdmin($request, $response, $container) {
    try {
        $stats = [
            'total_users' => 0,
            'total_events' => 0,
            'recent_users' => [],
            'recent_events' => []
        ];

        // Obtener estadísticas de usuarios
        try {
            $usuarisModel = new \Models\UsuarisPDO($container->config['db']);
            $stats['total_users'] = $usuarisModel->getTotalUsers();
            $stats['recent_users'] = $usuarisModel->getRecentUsers(5);
        } catch (\Exception $e) {
            error_log("Error obteniendo datos de usuarios: " . $e->getMessage());
        }

        // Obtener estadísticas de eventos
        try {
            $stats['total_events'] = $container->esdeveniments()->getTotalEvents();
            $stats['recent_events'] = $container->esdeveniments()->getRecentEvents(5);
        } catch (\Exception $e) {
            error_log("Error obteniendo datos de eventos: " . $e->getMessage());
        }

        $response->set("stats", $stats);
        $response->setTemplate("admin/dashboard.php");
        return $response;
        
    } catch (\Exception $e) {
        error_log("Error en panel admin: " . $e->getMessage());
        $response->set("error", $e->getMessage());
        $response->setTemplate("error.php");
        print_r($e);
        return $response;
    }
}

function ctrlAdminGetUser($request, $response, $container) {
    try {
        // Asegurarnos de que no hay salida previa
        ob_clean();
        
        $userId = $request->get('GET', 'id');
        error_log("ID recibido: " . $userId);

        if (!$userId) {
            throw new \Exception("ID de usuario no proporcionado");
        }

        $usuarisModel = $container->usuaris();
        $user = $usuarisModel->getUserById($userId);
        
        if (!$user) {
            throw new \Exception("Usuario no encontrado");
        }

        // Debug: ver qué datos estamos recibiendo
        error_log("Datos del usuario antes de JSON: " . print_r($user, true));

        // Preparar la respuesta
        $responseData = [
            'success' => true,
            'user' => $user
        ];

        // Convertir a JSON manualmente
        $jsonResponse = json_encode($responseData, JSON_UNESCAPED_UNICODE);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log("Error en la codificación JSON: " . json_last_error_msg());
            throw new \Exception("Error al procesar los datos");
        }

        // Establecer headers
        header('Content-Type: application/json');
        echo $jsonResponse;
        exit;

    } catch (\Exception $e) {
        $response->setHeader('Content-Type', 'application/json');
        $response->setJson();
        $response->set("success", false);
        $response->set("error", $e->getMessage());
        return $response;
    }
}

function ctrlAdminUpdateUser($request, $response, $container) {
    try {
        $data = json_decode(file_get_contents('php://input'), true);
        $result = $container->usuaris()->updateUser($data['id'], $data);
        $response->setJson(['success' => $result]);
    } catch (\Exception $e) {
        $response->setJson(['error' => $e->getMessage()], 500);
    }
    return $response;
}

function ctrlAdminDeleteUser($request, $response, $container) {
    try {
        $data = json_decode(file_get_contents('php://input'), true);
        $result = $container->usuaris()->deleteUser($data['id']);
        $response->setJson(['success' => $result]);
    } catch (\Exception $e) {
        $response->setJson(['error' => $e->getMessage()], 500);
    }
    return $response;
}

function ctrlAdminAddUser($request, $response, $container) {
    try {
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Validaciones básicas
        if (empty($data['nom']) || empty($data['email']) || empty($data['password'])) {
            throw new \Exception("Todos los campos son obligatorios");
        }

        // Hash de la contraseña
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        
        // Intentar crear el usuario (la verificación del email se hará en el modelo)
        $usuaris = $container->usuaris();
        $result = $usuaris->createUser($data);
        
        $response->setJson([
            'success' => true,
            'userId' => $result
        ]);
        
    } catch (\Exception $e) {
        $response->setJson([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
    return $response;
} 