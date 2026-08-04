<?php

$app = new Application();

$app->register(new DatabaseServiceProvider());
$app->register(new EventServiceProvider());

$app->boot();

return $app;