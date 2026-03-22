<?php

/* $router->get('/', function(Request $request) {
    return new Response(200, [], 'Home');
});

$router->get('/tasks', function(Request $request) {
    return new Response(200, [], 'Page tasks');
}); */

$router->get('/users/{id}', [UserController::class, 'show']);

$router->get('/', [HomeController::class, 'index']);
$router->get('/tasks', [HomeController::class, 'show']);