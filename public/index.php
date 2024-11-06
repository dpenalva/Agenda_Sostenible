<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

/**
 * Aquest fitxer és un exemple de Front Controller, pel qual passen totes les requests.
 */

 include "../src/config.php";
 include "../src/controllers/ctrlIndex.php";
 include "../src/controllers/ctrlJson.php";
 include "../src/controllers/ctrlEvents.php";
 include "../src/controllers/ctrlProfile.php";
 include "../src/controllers/ctrlAuth.php";

/**
  * Carreguem les classes del Framework Emeset
*/
  
 include "../src/Emeset/Container.php";
 include "../src/Emeset/Request.php";
 include "../src/Emeset/Response.php";

 $request = new \Emeset\Request();
 $response = new \Emeset\Response();
 $container = new \Emeset\Container($config);

 /* 
  * Aquesta és la part que fa que funcioni el Front Controller.
  * Si no hi ha cap paràmetre, carreguem la pàgina d'inici.
  * Si hi ha paràmetre, carreguem la pàgina que correspongui.
  * Si no existeix la pàgina, carreguem la pàgina d'error.
  */
 $r = '';
 if(isset($_REQUEST["r"])){
    $r = $_REQUEST["r"];
 }
 
 /* Front Controller, aquí es decideix quina acció s'executa */
 switch($r) {
    case "":
        $response = ctrlIndex($request, $response, $container);
        break;
    case "login":
        $response = ctrlLogin($request, $response, $container);
        break;
    case "register":
        $response = ctrlRegister($request, $response, $container);
        break;
    case "logout":
        $response = ctrlLogout($request, $response, $container);
        break;
    case "events":
        $response = ctrlEventsPage($request, $response, $container);
        break;
    case "profile":
        $response = ctrlProfile($request, $response, $container);
        break;
    case "api/events/create":
        $response = ctrlCreateEvent($request, $response, $container);
        break;
    case "api/events/get":
        $response = ctrlGetEvents($request, $response, $container);
        break;
    default:
        $response->set("error", "Ruta no encontrada");
        $response->setTemplate("404.php");
}

 /* Enviem la resposta al client. */
 $response->response();