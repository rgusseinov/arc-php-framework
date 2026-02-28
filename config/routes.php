<?php

$router->get('/', function(Request $request) {
    return new Response(200, [], 'Home');
});

$router->get('/tasks', function(Request $request) {
    return new Response(200, [], 'Page tasks');
});