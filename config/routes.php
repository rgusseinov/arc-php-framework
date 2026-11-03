<?php

use Arc\Framework\Controllers\HomeController;

$router->get('/', [HomeController::class, 'index']);