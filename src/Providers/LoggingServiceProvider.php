<?php

namespace Arc\Framework\Providers;

use Arc\Framework\Core\Container;
use Arc\Framework\Logging\Logger;
use Arc\Framework\Logging\LogLevel;
use Psr\Log\LoggerInterface;

class LoggingServiceProvider implements ServiceProviderInterface {
    public function register(Container $container): void {
        $container->singleton(LoggerInterface::class, function (Container $container) {
            $config = require __DIR__ . '/../../config/logging.php';
            return new Logger($config['path'], LogLevel::from($config['min_level']));
        });
    }

    public function boot(Container $container): void {

    }
}