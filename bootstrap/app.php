<?php

$app = new Application();

$app->register(new DatabaseServiceProvider());

$app->boot();

return $app;