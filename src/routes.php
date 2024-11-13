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
    'admin/getUser' => ['ctrlAdminGetUser', ['adminMiddleware']],
    'admin/updateUser' => ['ctrlAdminUpdateUser', ['adminMiddleware']],
    'events' => 'ctrlEvents',
    'toggleEventLike' => 'ctrlToggleEventLike',
    'admin/getEvent' => ['ctrlAdminGetEvent', ['adminMiddleware']],
    'admin/updateEvent' => ['ctrlAdminUpdateEvent', ['adminMiddleware']],
    'admin/deleteEvent' => ['ctrlAdminDeleteEvent', ['adminMiddleware']],
    // otras rutas...
]; 