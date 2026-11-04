<?php

declare(strict_types=1);

use Arc\Framework\Application\Application;
use Arc\Framework\Core\Config;
use Arc\Framework\Core\Env;

$app = new Application(dirname(__DIR__));

Env::load($app->basePath('.env'));

$config = new Config($app->basePath('config'));
$providers = $config->get('app.providers');

$app->getContainer()->singleton(Config::class, fn () => $config);

foreach ($providers as $provider) {
    $app->register(new $provider());
}

$app->boot();

return $app;