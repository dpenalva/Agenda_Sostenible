<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="/css/web.css" rel="stylesheet">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <h2 class="login-title">Bienvenido</h2>
            <form action="?r=login" method="post">
                <div class="form-floating mb-4">
                    <input type="email" class="form-control" id="email" name="email" placeholder="nombre@ejemplo.com" required>
                    <label for="email"><i class="fas fa-envelope me-2"></i>Email</label>
                </div>
                <div class="form-floating mb-4 position-relative">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" required>
                    <label for="password"><i class="fas fa-lock me-2"></i>Contraseña</label>
                    <span class="input-group-text" onclick="togglePassword()">
                        <i class="fas fa-eye" id="togglePassword"></i>
                    </span>
                </div>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger mb-4">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <button type="submit" class="login-btn w-100 mb-4">
                    Iniciar Sesión
                </button>

                <div class="text-center text-white">
                    <p>¿No tienes cuenta? <a href="/?r=register" class="register-link">Regístrate aquí</a></p>
                </div>
            </form>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('togglePassword');
            
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
