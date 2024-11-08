<?php

function ctrlAdmin($request, $response, $container) {
    try {
        $usuarisModel = new \Models\UsuarisPDO($container->config['db']);
        
        // Obtener estadísticas básicas
        $stats = [
            'total_users' => $usuarisModel->getTotalUsers(),
            'total_events' => $container->esdeveniments()->getTotalEvents(),
            'recent_users' => $usuarisModel->getRecentUsers(5),
            'recent_events' => $container->esdeveniments()->getRecentEvents(5)
        ];
        
        $response->set("stats", $stats);
        $response->setTemplate("admin/dashboard.php");
        return $response;
        
    } catch (\Exception $e) {
        $response->set("error", $e->getMessage());
        $response->setTemplate("error.php");
        return $response;
    }
} 