<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

/**
 * Aquest fitxer és un exemple de Front Controller, pel qual passen totes les requests.
 */

 include "../src/config.php";
 include "../src/middleware/auth.php";
 include "../src/middleware/adminAuth.php";
 include "../src/models/DB.php";
 include "../src/models/UsuarisPDO.php";
 include "../src/models/EsdevenimentsPDO.php";
 include "../src/controllers/ctrlIndex.php";
 include "../src/controllers/ctrlJson.php";
 include "../src/controllers/ctrlEvents.php";
 include "../src/controllers/ctrlProfile.php";
 include "../src/controllers/ctrlAuth.php";
 include "../src/controllers/ctrlAdmin.php";

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
 $r = $_REQUEST["r"] ?? '';

 // Apply authentication middleware to protected routes
 if (!in_array($r, ['login', 'register'])) {
     authMiddleware($request, $response, $container, function() {});
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
    case "updateProfileImage":
        $response = ctrlUpdateProfileImage($request, $response, $container);
        break;
    case "updateBanner":
        $response = ctrlUpdateBanner($request, $response, $container);
        break;
    case "updateProfile":
        $response = ctrlUpdateProfile($request, $response, $container);
        break;
    case "admin":
        $response = adminMiddleware($request, $response, $container, function($request, $response, $container) {
            return ctrlAdmin($request, $response, $container);
        });
        break;
    case "admin/getUser":
        $response = adminMiddleware($request, $response, $container, function($request, $response, $container) {
            return ctrlAdminGetUser($request, $response, $container);
        });
        break;
    case "admin/updateUser":
        $response = adminMiddleware($request, $response, $container, function($request, $response, $container) {
            return ctrlAdminUpdateUser($request, $response, $container);
        });
        break;
    case "admin/deleteUser":
        $response = adminMiddleware($request, $response, $container, function($request, $response, $container) {
            return ctrlAdminDeleteUser($request, $response, $container);
        });
        break;
    case "admin/addUser":
        $response = adminMiddleware($request, $response, $container, function($request, $response, $container) {
            return ctrlAdminAddUser($request, $response, $container);
        });
        break;
    default:
        $response->set("error", "Ruta no encontrada");
        $response->setTemplate("404.php");
}

 /* Enviem la resposta al client. */
 $response->response();

// Verificar si hay sesión activa
$isLoggedIn = isset($_SESSION['user_id']);
$response->set("isLoggedIn", $isLoggedIn);

if ($isLoggedIn) {
    try {
        $usuarisModel = $container->usuaris();
        $user = $usuarisModel->get($_SESSION['user_id']);
        $response->set("user", $user);
    } catch (\Exception $e) {
        session_destroy();
        $response->set("isLoggedIn", false);
        $response->set("error", "Error al cargar los datos del usuario");
    }
}