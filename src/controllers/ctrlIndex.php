<?php

function ctrlIndex($request, $response, $container) {
    // Eventos de ejemplo (esto luego vendrá de la base de datos)
    $eventos = [
        [
            'id' => 1,
            'title' => 'Conferencia de Desarrollo Web',
            'description' => 'Una conferencia sobre las últimas tendencias en desarrollo web',
            'event_date' => '2024-03-20',
            'event_time' => '15:00:00',
            'location' => 'Barcelona, España',
            'capacity' => 100,
            'event_type' => 'presencial',
            'image_url' => null,
            'username' => 'usuario_ejemplo',
            'avatar_url' => null,
            'created_at' => '2024-03-10 10:00:00'
        ],
        [
            'id' => 2,
            'title' => 'Taller de JavaScript',
            'description' => 'Aprende JavaScript desde cero',
            'event_date' => '2024-03-25',
            'event_time' => '18:00:00',
            'location' => 'Online',
            'capacity' => 50,
            'event_type' => 'virtual',
            'image_url' => null,
            'username' => 'profesor_js',
            'avatar_url' => null,
            'created_at' => '2024-03-11 09:00:00'
        ]
    ];

    $response->set("eventos", $eventos);
    $response->setTemplate("index.php");
    return $response;
}