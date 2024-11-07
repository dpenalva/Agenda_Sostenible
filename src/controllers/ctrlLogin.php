<?php

function ctrlLogin($request, $response, $container) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $usuarisModel = new \Models\UsuarisPDO($container->config['db']);
        $user = $usuarisModel->getByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            header("Location: /?r=profile");
            exit();
        } else {
            $response->set("error", "Credenciales incorrectas.");
        }
    } else {
        // Redirect to register page if not logged in
        if (!isset($_SESSION['user_id'])) {
            header("Location: /?r=register");
            exit();
        }
    }

    $response->setTemplate("login.php");
    return $response;
} 