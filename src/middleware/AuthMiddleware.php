<?php

function authMiddleware($request, $response, $container, $next) {
    if (!isset($_SESSION['user_id'])) {
        if ($request->isAjax()) {
            $response->setJson();
            $response->set("success", false);
            $response->set("message", "No autorizado");
            return $response;
        }
        header('Location: /?r=login');
        exit;
    }
    
    return $next($request, $response, $container);
} 