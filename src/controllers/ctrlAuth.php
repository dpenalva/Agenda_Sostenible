<?php

function ctrlLogin($request, $response, $container) {
    $response->setTemplate("login.php");
    return $response;
}

function ctrlRegister($request, $response, $container) {
    $response->setTemplate("register.php");
    return $response;
}

function ctrlLogout($request, $response, $container) {
    session_destroy();
    header("Location: /");
    exit();
} 