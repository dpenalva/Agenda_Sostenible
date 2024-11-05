<?php

function ctrlCreateEvent($request, $response, $container) {
    try {
        $eventData = $request->get('INPUT_POST', 'eventData');
        // Asegúrate de que la fecha esté en formato YYYY-MM-DD
        error_log('Fecha del evento: ' . $eventData['event_date']);
        // ... resto del código
    } catch (Exception $e) {
        // ... manejo de errores
    }
}

function ctrlGetEvents($request, $response, $container) {
    $eventRepository = $container->getEventRepository();
    $events = $eventRepository->getAll();
    
    $response->setJson();
    $response->set('events', $events);
    
    return $response;
} 