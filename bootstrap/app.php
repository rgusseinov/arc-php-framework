<?php

use Arc\Framework\Application\Application;
use Arc\Framework\Providers\DatabaseServiceProvider;
use Arc\Framework\Events\EventServiceProvider;

$app = new Application();

$app->register(new DatabaseServiceProvider());
$app->register(new EventServiceProvider());

$app->boot();

return $app;