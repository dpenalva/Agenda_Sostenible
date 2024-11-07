<?php

function ctrlLogout($request, $response, $container) {
    session_start();
    session_unset();
    session_destroy();
    header("Location: /?r=login");
    exit();
} 