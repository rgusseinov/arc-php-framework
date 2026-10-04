<?php

use Arc\Framework\Events\EventServiceProvider;
use Arc\Framework\Providers\DatabaseServiceProvider;
use Arc\Framework\Providers\LoggingServiceProvider;
use Arc\Framework\Providers\RouteServiceProvider;

return ['providers' => [
					DatabaseServiceProvider::class,
					EventServiceProvider::class,
					LoggingServiceProvider::class,
					RouteServiceProvider::class
		]
];
