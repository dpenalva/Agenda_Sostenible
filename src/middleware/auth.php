<?php

function authMiddleware($request, $response, $container, $next) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: /?r=login");
        exit();
    }

    return $next($request, $response, $container);
} 