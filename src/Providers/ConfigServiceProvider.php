<?php

namespace Arc\Framework\Providers;

use Arc\Framework\Core\Config;
use Arc\Framework\Core\Container;

class ConfigServiceProvider implements ServiceProviderInterface {
    public function register(Container $container): void {
        $container->singleton(Config::class, function (Container $container) {
            return new Config(dirname(__DIR__, 2) . '/config/');
        });
    }

    public function boot(Container $container): void {

    }
}