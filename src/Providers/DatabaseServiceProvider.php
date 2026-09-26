<?php

namespace Arc\Framework\Providers;

use Arc\Framework\Core\Container;

class DatabaseServiceProvider implements ServiceProviderInterface {
    public function register(Container $container): void {
        // echo "Database registered<br>";
    }

    public function boot(Container $container): void {
        // echo "Database booted<br>";
    }
}