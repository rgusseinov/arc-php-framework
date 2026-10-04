<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Arc\Framework\Core\HttpKernel;
use Arc\Framework\Http\Request;

require_once __DIR__ .'/../src/Helpers/helpers.php';

$app = require __DIR__ . '/../bootstrap/app.php';

$container = $app->getContainer();

$request = Request::fromGlobals();                                                                                                      

$kernel = new HttpKernel($container);

$response = $kernel->handle($request);

$response->send();