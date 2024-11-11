<?php

function ctrlEventsPage($request, $response, $container) {
    // Esta función renderiza la página de eventos
    $response->setTemplate("events.php");
    return $response;
}

function ctrlCreateEvent($request, $response, $container) {
    try {
        if (!isset($_SESSION['user_id'])) {
            throw new \Exception("Usuario no autenticado");
        }

        // Obtener el cuerpo de la petición JSON
        $jsonData = file_get_contents('php://input');
        $eventData = json_decode($jsonData, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("Error al procesar los datos del evento");
        }

        // Validar y limpiar los datos
        $cleanEventData = [
            'titol' => trim($eventData['titol'] ?? ''),
            'descripcio' => trim($eventData['descripcio'] ?? ''),
            'longitud' => trim($eventData['longitud'] ?? ''),
            'latitud' => trim($eventData['latitud'] ?? ''),
            'data_esdeveniment' => trim($eventData['data_esdeveniment'] ?? ''),
            'hora_esdeveniment' => trim($eventData['hora_esdeveniment'] ?? ''),
            'visibilitat_esdeveniment' => isset($eventData['visibilitat_esdeveniment']) ? 1 : 0,
            'id_usuari' => $_SESSION['user_id']
        ];

        // Validar campos requeridos
        $requiredFields = ['titol', 'data_esdeveniment', 'hora_esdeveniment'];
        foreach ($requiredFields as $field) {
            if (empty($cleanEventData[$field])) {
                throw new \Exception("El campo {$field} es requerido");
            }
        }

        $esdevenimentsModel = $container->esdeveniments();
        $id = $esdevenimentsModel->create($cleanEventData);

        $response->setJson();
        $response->set("success", true);
        $response->set("message", "Evento creado correctamente");
        $response->set("eventId", $id);
        
        return $response;

    } catch (\Exception $e) {
        $response->setJson();
        $response->set("success", false);
        $response->set("message", $e->getMessage());
        return $response;
    }
}

function ctrlGetEvents($request, $response, $container) {
    // TODO: Implementar cuando tengamos la base de datos
    $events = []; // Aquí irán los eventos de la base de datos
    
    $response->setJson();
    $response->set("events", $events);
    return $response;
} 