<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regístrate</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/web.css" rel="stylesheet">
</head>
<body>
    <div class="form-container">
        <h2 class="text-center">Regístrate</h2>
        <form action="?r=register" method="post" class="mt-4">
            <div class="mb-3">
                <label for="nom" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nom" name="nom" required>
            </div>
            <div class="mb-3">
                <label for="cognoms" class="form-label">Apellidos</label>
                <input type="text" class="form-control" id="cognoms" name="cognoms" required>
            </div>
            <div class="mb-3">
                <label for="nom_usuari" class="form-label">Nombre de Usuario</label>
                <input type="text" class="form-control" id="nom_usuari" name="nom_usuari" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Crear Cuenta</button>
        </form>
        <div class="text-center mt-3">
            <p>¿Ya tienes cuenta? <a href="/?r=login" class="btn btn-link">Inicia Sesión</a></p>
        </div>
    </div>
</body>
</html> 