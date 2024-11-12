<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="/css/web.css" rel="stylesheet">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <h2 class="login-title">Crear Cuenta</h2>
            <form action="?r=register" method="post">
                <div class="form-floating mb-4">
                    <input type="text" class="form-control" id="username" name="username" placeholder="Nombre de usuario" required>
                    <label for="username"><i class="fas fa-user me-2"></i>Nombre de usuario</label>
                </div>

                <div class="form-floating mb-4">
                    <input type="email" class="form-control" id="email" name="email" placeholder="nombre@ejemplo.com" required>
                    <label for="email"><i class="fas fa-envelope me-2"></i>Email</label>
                </div>

                <div class="form-floating mb-4 position-relative">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" required>
                    <label for="password"><i class="fas fa-lock me-2"></i>Contraseña</label>
                    <span class="input-group-text" onclick="togglePassword('password', 'togglePassword')">
                        <i class="fas fa-eye" id="togglePassword"></i>
                    </span>
                </div>

                <div class="form-floating mb-4 position-relative">
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirmar Contraseña" required>
                    <label for="confirm_password"><i class="fas fa-lock me-2"></i>Confirmar Contraseña</label>
                    <span class="input-group-text" onclick="togglePassword('confirm_password', 'toggleConfirmPassword')">
                        <i class="fas fa-eye" id="toggleConfirmPassword"></i>
                    </span>
                </div>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger mb-4">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <button type="submit" class="login-btn w-100 mb-4">
                    Registrarse
                </button>

                <div class="text-center text-white">
                    <p>¿Ya tienes cuenta? <a href="/?r=login" class="register-link">Inicia sesión aquí</a></p>
                </div>
            </form>
        </div>
    </div>

    <script>
        function togglePassword(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const toggleIcon = document.getElementById(iconId);
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html> 