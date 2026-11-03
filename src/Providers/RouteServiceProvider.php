<?php

namespace Arc\Framework\Providers;

use Arc\Framework\Core\Container;
use Arc\Framework\Core\Router;

class RouteServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container): void
    {
        $container->singleton(Router::class, function (Container $container) {
            return new Router();
        });
    }

    public function boot(Container $container): void
    {
        $router = $container->get(Router::class);

        require __DIR__ . '/../../config/routes.php';
    }
}
