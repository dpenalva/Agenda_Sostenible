<?php

$routes = [
    '' => 'ctrlIndex', // Ruta raíz
    'login' => 'ctrlLogin',
    'logout' => 'ctrlLogout',
    'profile' => 'ctrlProfile',
    'register' => 'ctrlRegister',
    'admin' => ['ctrlAdmin', ['adminMiddleware']],
    // otras rutas...
]; 