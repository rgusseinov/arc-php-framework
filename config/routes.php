<?php

use Arc\Framework\Controllers\HomeController;
use Arc\Framework\Controllers\UserController;

$router->get('/users/{id}', [UserController::class, 'show']);

$router->get('/', [HomeController::class, 'index']);
$router->get('/tasks', [HomeController::class, 'show']);