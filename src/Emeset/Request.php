<?php

/**
 * Exemple per a M07.
 *
 * @author: Dani Prados dprados@cendrassos.net
 *
 * Classe gestiona la petició HTTP.
 **/

namespace Emeset;

/**
 * Request: Classe gestiona la petició HTTP.
 *
 * @author: Dani Prados dprados@cendrassos.net
 *
 * Encapsula la petició HTTP per permetre llegir-la com una entrada.
 **/
class Request
{

    /**
     * __construct:  Crear el petició http
     **/
    public function __construct()
    {
        // Eliminamos session_start() de aquí ya que lo movimos al index.php
    }

    /**
     * get - Retorna el valor del paràmetre $key de la request.
     *
     * @param string $type tipus de paràmetre (GET, POST, FILES, REQUEST)
     * @param string $key  clau del paràmetre
     *
     * @return mixed
     */
    public function get($type, $key) {
        $result = null;
        
        switch ($type) {
            case 'GET':
                if (isset($_GET[$key])) {
                    $result = htmlspecialchars(strip_tags($_GET[$key]));
                }
                break;
            
            case 'POST':
                if (isset($_POST[$key])) {
                    $result = htmlspecialchars(strip_tags($_POST[$key]));
                }
                break;
            
            case 'FILES':
                if (isset($_FILES[$key])) {
                    $result = $_FILES[$key];
                }
                break;
            
            case 'SESSION':
                if (isset($_SESSION[$key])) {
                    $result = $_SESSION[$key];
                }
                break;
            
            case 'REQUEST':
                if (isset($_REQUEST[$key])) {
                    $result = htmlspecialchars(strip_tags($_REQUEST[$key]));
                }
                break;
        }
        
        return $result;
    }

    /**
     * getRaw:  obté un valor de l'entrada especificada sense filtrar
     *
     * @param $input   string identificador de l'entrada.
     * @param $id      string amb la tasca.
     * @param $options int opcions del filtre si volem un array FILTER_REQUIRE_ARRAY
     **/
    public function getRaw($input, $id, $options = 0)
    {
        return $this->get($input, $id, FILTER_DEFAULT, $options);
    }

     /**
     * get:  retorna true si l'entrada especificada existeix i false si no.
     *
     * @param $input   string identificador de l'entrada.
     * @param $id      string amb la tasca.
     * return boolean
     **/
    public function has($input, $id)
    {
        $result = false;
        if ($input === 'SESSION') {
            $result = isset($_SESSION[$id]);
        } elseif ($input === 'FILES') {
            $result = isset($_FILES[$id]);
        } elseif ($input === "INPUT_REQUEST") {
            $result = isset($_REQUEST[$id]);
        } else {
            $result = !is_null(filter_input($input, $id, FILTER_DEFAULT));
        }
        return $result;
    }

    /**
     * Verifica si la petición es AJAX
     */
    public function isAjax() {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    /**
     * Obtiene el método HTTP de la petición
     */
    public function getMethod() {
        return $_SERVER['REQUEST_METHOD'];
    }
}
