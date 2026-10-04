<?php

use Arc\Framework\Application\Application;

use Arc\Framework\Providers\DatabaseServiceProvider;
use Arc\Framework\Events\EventServiceProvider;
use Arc\Framework\Providers\LoggingServiceProvider;
use Arc\Framework\Providers\RouteServiceProvider;

$app = new Application();

$app->register(new DatabaseServiceProvider());
$app->register(new EventServiceProvider());
$app->register(new LoggingServiceProvider());
$app->register(new RouteServiceProvider());

$app->boot();

return $app;