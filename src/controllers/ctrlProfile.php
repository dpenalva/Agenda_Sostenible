<?php

function ctrlProfile($request, $response, $container) {
    // TODO: Aquí añadiremos la lógica para obtener los datos del usuario
    // y sus eventos cuando implementemos la base de datos
    
    $userData = [
        'username' => 'Usuario Ejemplo',
        'bio' => 'Esta es mi biografía de ejemplo',
        'avatar_url' => '/public/images/default-avatar.png',
        'events' => [] // Aquí irán los eventos del usuario
    ];
    
    $response->set("userData", $userData);
    $response->setTemplate("profile.php");
    return $response;
} 