<?php

function adminMiddleware($request, $response, $container, $next) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
        if ($request->isAjax()) {
            $response->setJson();
            $response->set("success", false);
            $response->set("redirect", "/?r=login");
            return $response;
        }
        header("Location: /?r=login");
        exit();
    }

    if ($_SESSION['user_role'] !== 'admin') {
        if ($request->isAjax()) {
            $response->setJson();
            $response->set("success", false);
            $response->set("message", "Acceso denegado");
            return $response;
        }
        header("Location: /?r=403");
        exit();
    }

    return $next($request, $response, $container);
} 