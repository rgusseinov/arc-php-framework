<?php

namespace Arc\Framework\Providers;

use Arc\Framework\Core\Config;
use Arc\Framework\Core\Container;
use Arc\Framework\Database\Connection;
use RuntimeException;

class DatabaseServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container): void
    {
        $container->singleton(Connection::class, function (Container $container) {
            $config = $container->get(Config::class);

            foreach (['host' => 'DB_HOST', 'name' => 'DB_NAME', 'user' => 'DB_USER'] as $key => $env) {
                $value = $config->get('database.' . $key);

                if (!is_string($value) || $value === '') {
                    throw new RuntimeException("Database is not configured: set {$env} in .env");
                }
            }

            $password = $config->get('database.password');

            return new Connection(
                'mysql:host=' . $config->get('database.host') . ';dbname=' . $config->get('database.name'),
                (string) $config->get('database.user'),
                is_string($password) ? $password : ''
            );
        });
    }

    public function boot(Container $container): void
    {
    }
}
