<?php

declare(strict_types=1);

use Arc\Framework\Application\Application;
use Arc\Framework\Core\Config;

require_once __DIR__ . '/../Env.php';
Env::load(__DIR__ . '/../.env');

$app = new Application();

$config = new Config(__DIR__ . '/../config');
$providers = $config->get('app.providers');

$app->getContainer()->singleton(Config::class, fn () => $config);

foreach ($providers as $provider){
	$app->register(new $provider());
}

$app->boot();

return $app;