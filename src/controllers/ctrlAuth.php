<?php

function ctrlLogin($request, $response, $container) {
    try {
        if ($request->getMethod() === 'POST') {
            $email = $request->get('POST', 'email');
            $password = $request->get('POST', 'password');

            error_log("Intento de login - Email: " . $email);

            if (empty($email) || empty($password)) {
                throw new \Exception("Todos los campos son obligatorios");
            }

            $usuarisModel = new \Models\UsuarisPDO($container->config['db']);
            $user = $usuarisModel->login($email, $password);

            error_log("Login exitoso - Usuario: " . $user['nom'] . " - Rol: " . $user['rol']);

            if ($user) {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }

                error_log("Iniciando sesión para usuario - ID: " . $user['id'] . " - Rol: " . $user['rol']);
                
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['nom'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['rol'];

                error_log("Sesión iniciada - Datos de sesión: " . print_r($_SESSION, true));

                if ($request->isAjax()) {
                    $response->setJson();
                    $response->set("success", true);
                    $response->set("redirect", "/");
                    return $response;
                }

                header("Location: /");
                exit();
            }
        }

        $response->setTemplate("login.php");
        return $response;

    } catch (\Exception $e) {
        error_log("Error en login: " . $e->getMessage());
        
        if ($request->isAjax()) {
            $response->setJson();
            $response->set("success", false);
            $response->set("message", $e->getMessage());
            return $response;
        }

        $response->set("error", $e->getMessage());
        $response->setTemplate("login.php");
        return $response;
    }
}

function ctrlRegister($request, $response, $container) {
    try {
        if ($request->getMethod() === 'POST') {
            // Mapear los campos del formulario a los nombres de la base de datos
            $data = [
                'nombre' => $request->get('POST', 'nombre'),
                'apellido' => $request->get('POST', 'apellido'),
                'username' => $request->get('POST', 'username'),
                'email' => $request->get('POST', 'email'),
                'password' => $request->get('POST', 'password')
            ];

            // Validaciones básicas
            foreach ($data as $key => $value) {
                if (empty($value)) {
                    throw new \Exception("El campo $key es obligatorio");
                }
            }

            // Validar email
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                throw new \Exception("El email no es válido");
            }

            // Validar longitud de la contraseña
            if (strlen($data['password']) < 6) {
                throw new \Exception("La contraseña debe tener al menos 6 caracteres");
            }

            $usuarisModel = new \Models\UsuarisPDO($container->config['db']);
            $userId = $usuarisModel->register($data);

            if ($userId) {
                // Iniciar sesión automáticamente
                $_SESSION['user_id'] = $userId;
                $_SESSION['user_name'] = $data['nombre'];
                $_SESSION['user_email'] = $data['email'];
                $_SESSION['user_role'] = 'user';

                header("Location: /");
                exit();
            }
        }

        $response->setTemplate("register.php");
        return $response;

    } catch (\Exception $e) {
        $response->set("error", $e->getMessage());
        $response->setTemplate("register.php");
        return $response;
    }
}

function ctrlLogout($request, $response, $container) {
    session_destroy();
    
    if ($request->isAjax()) {
        $response->setJson();
        $response->set("success", true);
        $response->set("redirect", "/");
        return $response;
    }
    
    header("Location: /");
    exit();
} 