<?php

function ctrlCreateEvent($request, $response, $container) {
    try {
        // Validar datos requeridos
        $title = $request->get('POST', 'title');
        $date = $request->get('POST', 'date');
        $time = $request->get('POST', 'time');

        if (!$title || !$date || !$time) {
            throw new Exception('Faltan campos requeridos');
        }

        $eventData = [
            'title' => $title,
            'description' => $request->get('POST', 'description'),
            'event_date' => $date,
            'event_time' => $time,
            'location' => $request->get('POST', 'location'),
            'capacity' => $request->get('POST', 'capacity'),
            'event_type' => $request->get('POST', 'type'),
            'user_id' => $request->get('SESSION', 'user_id', FILTER_SANITIZE_NUMBER_INT) ?? 1
        ];

        // Manejar la imagen
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/uploads/events/';
            
            // Crear el directorio si no existe
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileName = uniqid() . '_' . basename($_FILES['image']['name']);
            $uploadFile = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                $eventData['image_url'] = $fileName;
            } else {
                throw new Exception('Error al subir la imagen');
            }
        }

        $eventRepository = $container->getEventRepository();
        $result = $eventRepository->create($eventData);

        if (!$result) {
            throw new Exception('Error al crear el evento en la base de datos');
        }

        $response->setJson();
        $response->set('status', 'success');
        $response->set('message', 'Evento creado correctamente');

    } catch (Exception $e) {
        $response->setJson();
        $response->set('status', 'error');
        $response->set('message', $e->getMessage());
    }

    return $response;
}

function ctrlGetEvents($request, $response, $container) {
    $eventRepository = $container->getEventRepository();
    $events = $eventRepository->getAll();
    
    $response->setJson();
    $response->set('events', $events);
    
    return $response;
} 