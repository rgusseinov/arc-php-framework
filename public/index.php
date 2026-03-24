<?php

require_once __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/../src/Http/Request.php';
require_once __DIR__ . '/../src/Http/Response.php';
require_once __DIR__ . '/../src/Core/Container.php';
require_once __DIR__ .'/../src/Core/Router.php';
require_once __DIR__ .'/../src/Core/Route.php';
require_once __DIR__ .'/../src/Core/HttpKernel.php';
require_once __DIR__ .'/../src/Middleware/MiddlewarePipeline.php';
require_once __DIR__ .'/../src/Middleware/MiddlewareInterface.php';
require_once __DIR__ .'/../src/Middleware/LoggingMiddleware.php';

require_once __DIR__ .'/../src/Controller/HomeController.php';
require_once __DIR__ .'/../src/Controller/UserController.php';

$request = Request::fromGlobals();

$container = new Container();
$router = new Router();

require_once __DIR__  . '/../config/routes.php';

$kernel = new HttpKernel($router, $container);

$response = $kernel->handle($request);

$response->send();

/*
Browser
→ public/index.php (Front Controller)
→ HttpKernel::handle()
→ Router::match()
→ Closure Handler
→ Response
→ Response::send()
→ Browser Output
*/
