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