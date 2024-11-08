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
        $id = $request->get('id', null);
        if (empty($id)) {
            throw new \Exception("ID de usuario no proporcionado");
        }

        $usuarisModel = new \Models\UsuarisPDO($container->config['db']);
        $user = $usuarisModel->getUserById($id);
        
        if (!$user) {
            throw new \Exception("Usuario no encontrado");
        }

        // Establece correctamente la respuesta JSON
        $response->setJson(['user' => $user]);
        return $response;

    } catch (\Exception $e) {
        error_log("Error en getUser: " . $e->getMessage());
        $response->setJson(['error' => $e->getMessage()]);
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