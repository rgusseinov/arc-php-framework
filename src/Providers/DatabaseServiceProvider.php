<?php

namespace Arc\Framework\Providers;

use Arc\Framework\Core\Config;
use Arc\Framework\Core\Container;
use Arc\Framework\Database\Connection;

class DatabaseServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container): void
    {
        $container->singleton(Connection::class, function (Container $container) {
            $config = $container->get(Config::class);

            return new Connection(
                "mysql:host=" . $config->get('database.host') . ";dbname=" . $config->get('database.name'),
                $config->get('database.user'),
                $config->get('database.password')
            );
        });
    }

    public function boot(Container $container): void
    {
        // echo "Database booted<br>";
    }
}
