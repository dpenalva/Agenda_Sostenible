<?php

function adminMiddleware($request, $response, $container, $next) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id'])) {
        if ($request->isAjax()) {
            $response->setJson();
            $response->set("success", false);
            $response->set("message", "Acceso no autorizado");
            return $response;
        }
        
        header("Location: /?r=login");
        exit();
    }

    try {
        $usuarisModel = new \Models\UsuarisPDO($container->config['db']);
        if (!$usuarisModel->isAdmin($_SESSION['user_id'])) {
            if ($request->isAjax()) {
                $response->setJson();
                $response->set("success", false);
                $response->set("message", "Acceso denegado");
                return $response;
            }
            
            header("Location: /?r=403");
            exit();
        }
    } catch (\Exception $e) {
        if ($request->isAjax()) {
            $response->setJson();
            $response->set("success", false);
            $response->set("message", $e->getMessage());
            return $response;
        }
        
        header("Location: /?r=error");
        exit();
    }

    return $next($request, $response, $container);
} 