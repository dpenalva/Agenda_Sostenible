<?php

function ctrlEvents($request, $response, $container) {
    try {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /?r=login");
            exit();
        }

        $esdevenimentsModel = $container->esdeveniments();
        $savedEvents = $esdevenimentsModel->getLikedEventsWithDetails($_SESSION['user_id']);
        
        if (empty($savedEvents)) {
            // Si no hay eventos, enviamos un array vacío en lugar de null
            $savedEvents = [];
        }
        
        $response->set('savedEvents', $savedEvents);
        $response->setTemplate('events.php');
        
        return $response;
    } catch (\Exception $e) {
        error_log("Error en ctrlEvents: " . $e->getMessage());
        $response->set('error', "Ha ocurrido un error al cargar los eventos favoritos.");
        $response->setTemplate('error.php');
        return $response;
    }
}

function ctrlToggleEventLike($request, $response, $container) {
    try {
        if (!isset($_SESSION['user_id'])) {
            throw new \Exception("Usuario no autenticado");
        }

        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data['eventId'])) {
            throw new \Exception("ID de evento no proporcionado");
        }

        $eventId = $data['eventId'];
        $esdevenimentsModel = $container->esdeveniments();
        $result = $esdevenimentsModel->toggleLike($_SESSION['user_id'], $eventId);
        
        // Obtener los datos del evento
        $event = $esdevenimentsModel->get($eventId);
        
        $response->setJson();
        $response->set('success', true);
        $response->set('isLiked', $result['isLiked']);
        $response->set('likesCount', $result['likesCount']);
        $response->set('event', $event); // Añadir datos del evento
        
        return $response;
    } catch (\Exception $e) {
        $response->setJson();
        $response->set('success', false);
        $response->set('message', $e->getMessage());
        return $response;
    }
}

function ctrlCreateEvent($request, $response, $container) {
    try {
        if (!isset($_SESSION['user_id'])) {
            throw new \Exception("Usuario no autenticado");
        }

        $data = json_decode(file_get_contents('php://input'), true);
        
        // Validar datos requeridos
        $requiredFields = ['titol', 'descripcio', 'data_esdeveniment', 'hora_esdeveniment'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                throw new \Exception("El campo {$field} es requerido");
            }
        }

        $esdevenimentsModel = $container->esdeveniments();
        $result = $esdevenimentsModel->create([
            'titol' => $data['titol'],
            'descripcio' => $data['descripcio'],
            'data_esdeveniment' => $data['data_esdeveniment'],
            'hora_esdeveniment' => $data['hora_esdeveniment'],
            'longitud' => $data['longitud'] ?? null,
            'latitud' => $data['latitud'] ?? null,
            'visibilitat_esdeveniment' => $data['visibilitat_esdeveniment'] ?? 0,
            'tipus_esdeveniment_id' => $data['tipus_esdeveniment_id'] ?? 1,
            'id_usuari' => $_SESSION['user_id']
        ]);

        $response->setJson();
        $response->set('success', true);
        $response->set('eventId', $result);
        
        return $response;
    } catch (\Exception $e) {
        $response->setJson();
        $response->set('success', false);
        $response->set('message', $e->getMessage());
        return $response;
    }
}

function ctrlGetEvents($request, $response, $container) {
    try {
        $esdevenimentsModel = $container->esdeveniments();
        $events = $esdevenimentsModel->getAll();

        $response->setJson();
        $response->set('success', true);
        $response->set('events', $events);
        
        return $response;
    } catch (\Exception $e) {
        $response->setJson();
        $response->set('success', false);
        $response->set('message', $e->getMessage());
        return $response;
    }
}

function ctrlDeleteEvent($request, $response, $container) {
    try {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            throw new \Exception("No tienes permisos para realizar esta acción");
        }

        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data['eventId'])) {
            throw new \Exception("ID de evento no proporcionado");
        }

        $eventId = $data['eventId'];
        $esdevenimentsModel = $container->esdeveniments();
        $result = $esdevenimentsModel->deleteEvent($eventId);
        
        $response->setJson();
        $response->set('success', true);
        return $response;
    } catch (\Exception $e) {
        $response->setJson();
        $response->set('success', false);
        $response->set('message', $e->getMessage());
        return $response;
    }
}

function ctrlEventDetails($request, $response, $container) {
    try {
        // Usar filter_input directamente
        $eventId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        
        if (!$eventId) {
            throw new \Exception("ID de evento no válido");
        }

        $esdevenimentsModel = $container->esdeveniments();
        $event = $esdevenimentsModel->get($eventId);
        
        if (!$event) {
            throw new \Exception("Evento no encontrado");
        }

        $response->set("title", $event['titol']);
        $response->set("event", $event);
        $response->setTemplate("eventDetails.php");

    } catch (\Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        header("Location: ?r=events");
        exit;
    }

    return $response;
}

function ctrlEventsGetEvent($request, $response, $container) {
    try {
        if (!isset($_GET['id'])) {
            throw new \Exception("ID de evento no proporcionado");
        }

        $eventId = $_GET['id'];
        $esdevenimentsModel = $container->esdeveniments();
        $event = $esdevenimentsModel->get($eventId);

        if (!$event) {
            throw new \Exception("Evento no encontrado");
        }

        $response->setJson();
        $response->set('success', true);
        $response->set('event', $event);
        
    } catch (\Exception $e) {
        $response->setJson();
        $response->set('success', false);
        $response->set('error', $e->getMessage());
    }
    
    return $response;
}