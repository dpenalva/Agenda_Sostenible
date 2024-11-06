<?php

function ctrlLoginPage($request, $response, $container) {
    $response->setTemplate("auth/login.php");
    return $response;
}

function ctrlLoginApi($request, $response, $container) {
    try {
        // Asegurarnos de que es una petición POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            throw new Exception('Método no permitido');
        }

        // Obtener datos del request
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['username']) || !isset($data['password'])) {
            throw new Exception('Faltan datos requeridos');
        }

        $db = $container->get('db');
        
        // Buscar usuario
        $stmt = $db->prepare("SELECT id, nom_usuari, contrasenya FROM usuaris WHERE nom_usuari = ?");
        $stmt->execute([$data['username']]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($data['password'], $user['contrasenya'])) {
            throw new Exception('Credenciales incorrectas');
        }

        // Login exitoso
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['nom_usuari'];

        // Configurar respuesta JSON
        $response->setJson();
        $response->set('success', true);
        $response->set('message', 'Login correcto');
        $response->set('redirect', '/');
        
        return $response;

    } catch (Exception $e) {
        $response->setJson();
        $response->set('success', false);
        $response->set('message', $e->getMessage());
        
        return $response;
    }
}

function ctrlRegisterApi($request, $response, $container) {
    try {
        header('Content-Type: application/json');
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Validar datos
        if (!isset($data['username']) || !isset($data['password']) || !isset($data['email'])) {
            throw new Exception("Faltan campos requeridos");
        }

        $db = $container->get('db');
        
        // Verificar si el usuario ya existe
        $stmt = $db->prepare("SELECT id FROM usuaris WHERE nom_usuari = ? OR email = ?");
        $stmt->execute([$data['username'], $data['email']]);
        if ($stmt->fetch()) {
            throw new Exception("El usuario o email ya existe");
        }

        // Crear usuario
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        $stmt = $db->prepare("
            INSERT INTO usuaris (nom_usuari, email, contrasenya) 
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$data['username'], $data['email'], $hashedPassword]);

        echo json_encode([
            "success" => true,
            "message" => "Usuario registrado correctamente"
        ]);
        exit;
        
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => $e->getMessage()
        ]);
        exit;
    }
}

function ctrlLogout($request, $response, $container) {
    session_destroy();
    header('Location: /');
    exit;
} 