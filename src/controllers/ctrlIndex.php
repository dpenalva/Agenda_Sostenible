<?php

function ctrlIndex($request, $response, $container) {
    try {
        // Obtener los eventos usando el modelo
        $esdevenimentsModel = $container->esdeveniments();
        $eventos = $esdevenimentsModel->getAll(); // Necesitaremos crear este método
        
        // Pasar los eventos a la vista
        $response->set("eventos", $eventos);
        $response->setTemplate("index.php");
        return $response;
    } catch (Exception $e) {
        // Manejar el error apropiadamente
        $response->set("error", $e->getMessage());
        $response->setTemplate("error.php");
        return $response;
    }
}