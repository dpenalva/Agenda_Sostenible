<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/web.css" rel="stylesheet">
</head>
<body class="bg-dark text-white">
    <div class="container text-center py-5">
        <h1 class="display-1">Error</h1>
        <p class="lead"><?php echo htmlspecialchars($error ?? 'Ha ocurrido un error inesperado'); ?></p>
        <a href="/" class="btn btn-outline-light mt-3">Volver al inicio</a>
    </div>
</body>
</html> 