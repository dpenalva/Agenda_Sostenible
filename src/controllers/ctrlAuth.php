<?php

function ctrlLogin($request, $response, $container) {
    try {
        if ($request->getMethod() === 'POST') {
            $email = $request->get('POST', 'email');
            $password = $request->get('POST', 'password');

            if (empty($email) || empty($password)) {
                throw new \Exception("Todos los campos son obligatorios");
            }

            $usuarisModel = new \Models\UsuarisPDO($container->config['db']);
            $user = $usuarisModel->login($email, $password);

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nom'];
            $_SESSION['user_email'] = $user['email'];

            if ($request->isAjax()) {
                $response->setJson();
                $response->set("success", true);
                $response->set("redirect", "/?r=profile");
                return $response;
            }

            header("Location: /?r=profile");
            exit();
        }

        $response->setTemplate("login.php");
        return $response;

    } catch (\Exception $e) {
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
            // Recoger los datos del formulario
            $data = [
                'nom' => $request->get('POST', 'nom'),
                'cognoms' => $request->get('POST', 'cognoms'),
                'nom_usuari' => $request->get('POST', 'nom_usuari'),
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
                $_SESSION['user_name'] = $data['nom'];
                $_SESSION['user_email'] = $data['email'];

                if ($request->isAjax()) {
                    $response->setJson();
                    $response->set("success", true);
                    $response->set("message", "Registro exitoso");
                    $response->set("redirect", "/");
                    return $response;
                }

                header("Location: /");
                exit();
            }
        }

        // Si no es POST, mostrar el formulario
        $response->setTemplate("register.php");
        return $response;

    } catch (\Exception $e) {
        error_log("Error en registro: " . $e->getMessage());

        if ($request->isAjax()) {
            $response->setJson();
            $response->set("success", false);
            $response->set("message", $e->getMessage());
            return $response;
        }

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