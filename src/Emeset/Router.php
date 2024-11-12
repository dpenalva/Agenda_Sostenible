<?php

// ... código existente ...
if (isset($routes[$route])) {
    $action = $routes[$route];
    return $action($request, $response, $container);
} else {
    // Manejar ruta no encontrada
    header("HTTP/1.0 404 Not Found");
    return null;
} 