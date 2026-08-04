<?php

require_once __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/../src/Http/Request.php';
require_once __DIR__ . '/../src/Http/Response.php';
require_once __DIR__ . '/../src/Core/Container.php';
require_once __DIR__ .'/../src/Core/Router.php';
require_once __DIR__ .'/../src/Core/Route.php';
require_once __DIR__ .'/../src/Core/HttpKernel.php';
require_once __DIR__ .'/../src/Middlewares/MiddlewarePipeline.php';
require_once __DIR__ .'/../src/Middlewares/MiddlewareInterface.php';
require_once __DIR__ .'/../src/Middlewares/LoggingMiddleware.php';

require_once __DIR__ .'/../src/Controllers/HomeController.php';
require_once __DIR__ .'/../src/Controllers/UserController.php';

require_once __DIR__ .'/../src/Helpers/helpers.php';

$app = require __DIR__ . '/../bootstrap/app.php';

$container = $app->getContainer();

$dispatcher = $container->get(EventDispatcher::class);

/* class OrderCreated implements Event {}
$dispatcher->dispatch(new OrderCreated()); */

$request = Request::fromGlobals();

$container = new Container();
$router = new Router();

require_once __DIR__  . '/../config/routes.php';

$kernel = new HttpKernel($router, $container);

$response = $kernel->handle($request);

$response->send();