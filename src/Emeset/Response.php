<?php

/**
 * Exemple per a M07.
 *
 * @author: Dani Prados dprados@cendrassos.net
 *
 * Objecte que encapsula la resposta.
 **/

namespace Emeset;

/**
 * Response: Objecte que encapsula la resposta.
 *
 * Per guarda tota la informació de la resposta.
 **/
class Response
{
    private $data = [];
    private $template;
    private $path;
    private $header = false;
    private $redirect = false;
    private $json = false;

    /**
     * __construct:  Té tota la informació per crear la resposta
     *
     * @param $path string path fins a la carpeta de plantilles.
     **/
    public function __construct($path = "../src/views/")
    {
        $this->path = $path;
    }

    /**
     * set:  obté un valor de l'entrada especificada amb el filtre indicat
     *
     * @param $id    string identificador del valor que deem.
     * @param $value mixed valor a desar
     **/
    public function set($id, $value)
    {
        $this->data[$id] = $value;
    }

    /**
     * setSession guarda un valor a la sessió
     *
     * @param  string $id    clau del valor que volem desar
     * @param  mixed  $value variable que volem desar
     * @return void
     */
    public function setSession($id, $value)
    {
        $_SESSION[$id] = $value;
    }

    /**
     * Esborra el valor indicat de la sessió
     *
     * @param $id
     * @return void
     */
    public function unsetSession($id)
    {
        unset($_SESSION[$id]);
    }
    
    /**
     * setCookie funció afegida per consistència crea una cookie.
     *
     * Accepta exament els mateixos paràmetres que la funció setcookie de php.
     *
     * @param  string  $name
     * @param  string  $value
     * @param  integer $expire
     * @param  string  $path
     * @param  string  $domain
     * @param  boolean $secure
     * @param  boolean $httponly
     * @return void
     */
    public function setCookie($name, $value = "", $expire = 0, $path = "", $domain = "", $secure = false, $httponly = false)
    {
        setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
    }

    /**
     * setHeader Afegeix una capçalera http a la resposta
     *
     * @param  string $header capçalera http
     * @return void
     */
    public function setHeader($header)
    {
        $this->header = $header;
    }

    /**
     * redirect.  Defineix la resposta com una redirecció. (accepta els mateixos paràmetres que header)
     *
     * @param  string $header capçalera http amb la
     *                        redirecció
     * @return void
     */
    public function redirect($header)
    {
        $this->setHeader($header);
        $this->redirect = true;
    }

    /**
     * setTemplate defineix quina plantilla utilitzarem per la resposta.
     *
     * @param  string $p nom de la plantilla
     * @return void
     */
    public function setTemplate($p)
    {
        $this->template = $p;
    }

    /**
     * setJson força que la resposta sigui en format json.
     *
     * @return void
     */
    public function setJson()
    {
        $this->json = true;
    }

    /**
     * Genera la resposta HTTP
     *
     * @return void
     */
    public function response()
    {
        if ($this->redirect) {
            header($this->header);
            exit();
        } elseif ($this->json) {
            header('Content-Type: application/json');
            echo json_encode($this->data);
        } else {
            if ($this->header !== false) {
                header($this->header);
            }
            extract($this->data);
            $viewPath = __DIR__ . '/../views/' . $this->template;

            if (file_exists($viewPath)) {
                include $viewPath;
            } else {
                // Maneja el error si el archivo no existe
                echo "Error: La vista de login no se encuentra.";
            }
        }
    }
}
