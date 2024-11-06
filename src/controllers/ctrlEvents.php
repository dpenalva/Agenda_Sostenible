<?php

function ctrlEventsPage($request, $response, $container) {
    // Esta función renderiza la página de eventos
    $response->setTemplate("events.php");
    return $response;
}

function ctrlCreateEvent($request, $response, $container) {
    try {
        $eventData = $request->get('INPUT_POST', 'eventData');
        // Validación y procesamiento del evento
        $response->setJson();
        $response->set("success", true);
        $response->set("message", "Evento creado correctamente");
    } catch (Exception $e) {
        $response->setJson();
        $response->set("success", false);
        $response->set("message", $e->getMessage());
    }
    return $response;
}

function ctrlGetEvents($request, $response, $container) {
    // TODO: Implementar cuando tengamos la base de datos
    $events = []; // Aquí irán los eventos de la base de datos
    
    $response->setJson();
    $response->set("events", $events);
    return $response;
} 