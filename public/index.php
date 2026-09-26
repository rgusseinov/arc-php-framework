<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Arc\Framework\Core\HttpKernel;
use Arc\Framework\Http\Request;
use Arc\Framework\Http\Response;
use Arc\Framework\Middlewares\MiddlewarePipeline;
use Arc\Framework\Middlewares\LoggingMiddleware;
use Arc\Framework\Events\EventDispatcher;
use Arc\Framework\Core\Router;
use Arc\Framework\Core\Route;

require_once __DIR__ .'/../src/Helpers/helpers.php';

$app = require __DIR__ . '/../bootstrap/app.php';

$container = $app->getContainer();

$dispatcher = $container->get(EventDispatcher::class);

$request = Request::fromGlobals();
$router = new Router();

require_once __DIR__  . '/../config/routes.php';

$kernel = new HttpKernel($router, $container);

$response = $kernel->handle($request);

$response->send();