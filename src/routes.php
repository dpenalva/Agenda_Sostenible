<?php

$routes = [
    '' => 'ctrlIndex', // Ruta raíz
    'login' => 'ctrlLogin',
    'logout' => 'ctrlLogout',
    'profile' => 'ctrlProfile',
    'register' => 'ctrlRegister',
    'admin' => ['ctrlAdmin', ['adminMiddleware']],
    'admin/users' => ['ctrlAdmin', ['adminMiddleware']],
    'admin/events' => ['ctrlAdmin', ['adminMiddleware']],
    // otras rutas...
]; 