<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/web.css" rel="stylesheet">
</head>
<body>
    <div class="form-container">
        <h2 class="text-center">Iniciar Sesión</h2>
        <form action="?r=login" method="post" class="mt-4">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>
        </form>
        <div class="text-center mt-3">
            <p>¿No tienes cuenta? <a href="/?r=register" class="btn btn-link">Regístrate</a></p>
        </div>
    </div>
</body>
</html>
