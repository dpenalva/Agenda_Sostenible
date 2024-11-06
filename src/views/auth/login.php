<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Agenda Sostenible</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/web.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .auth-container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #2c3034;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.3);
        }
        .logo-container {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo-container img {
            max-width: 150px;
            margin-bottom: 20px;
        }
        .form-control {
            background-color: #1a1e21;
            border: 1px solid #53d5cd;
            color: white;
            margin-bottom: 15px;
        }
        .form-control:focus {
            background-color: #1a1e21;
            border-color: #53d5cd;
            color: white;
            box-shadow: 0 0 0 0.25rem rgba(83, 213, 205, 0.25);
        }
        .btn-primary {
            background-color: #53d5cd;
            border-color: #53d5cd;
        }
        .btn-primary:hover {
            background-color: #45a29e;
            border-color: #45a29e;
        }
        .auth-links {
            text-align: center;
            margin-top: 20px;
        }
        .auth-links a {
            color: #53d5cd;
            text-decoration: none;
        }
        .auth-links a:hover {
            color: #45a29e;
        }
    </style>
</head>
<body class="bg-dark">
    <div class="container min-vh-100 d-flex align-items-center justify-content-center">
        <div class="auth-container">
            <div class="logo-container">
                <img src="/uploads/images/logo1.png" alt="Logo" class="img-fluid">
                <h2 class="text-white">Agenda Sostenible</h2>
            </div>

            <!-- Formulario de Login -->
            <form id="loginForm" class="<?php echo !isset($_GET['register']) ? '' : 'd-none'; ?>">
                <h3 class="text-white text-center mb-4">Iniciar Sesión</h3>
                <div class="mb-3">
                    <input type="text" class="form-control" id="loginUsername" placeholder="Nombre de usuario">
                </div>
                <div class="mb-3">
                    <input type="password" class="form-control" id="loginPassword" placeholder="Contraseña">
                </div>
                <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>
                <div class="auth-links">
                    <p class="text-white">¿No tienes cuenta? <a href="?r=login&register=true">Regístrate</a></p>
                </div>
            </form>

            <!-- Formulario de Registro -->
            <form id="registerForm" class="<?php echo isset($_GET['register']) ? '' : 'd-none'; ?>">
                <h3 class="text-white text-center mb-4">Registro</h3>
                <div class="mb-3">
                    <input type="text" class="form-control" id="registerUsername" placeholder="Nombre de usuario">
                </div>
                <div class="mb-3">
                    <input type="text" class="form-control" id="registerNombre" placeholder="Nombre">
                </div>
                <div class="mb-3">
                    <input type="text" class="form-control" id="registerApellidos" placeholder="Apellidos">
                </div>
                <div class="mb-3">
                    <input type="email" class="form-control" id="registerEmail" placeholder="Email">
                </div>
                <div class="mb-3">
                    <input type="password" class="form-control" id="registerPassword" placeholder="Contraseña">
                </div>
                <div class="mb-3">
                    <input type="password" class="form-control" id="confirmPassword" placeholder="Confirmar contraseña">
                </div>
                <button type="submit" class="btn btn-primary w-100">Registrarse</button>
                <div class="auth-links">
                    <p class="text-white">¿Ya tienes cuenta? <a href="?r=login">Iniciar Sesión</a></p>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/js/auth.js"></script>
</body>
</html> 