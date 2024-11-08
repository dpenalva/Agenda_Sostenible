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