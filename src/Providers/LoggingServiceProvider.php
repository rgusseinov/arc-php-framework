<?php

namespace Arc\Framework\Providers;

use Arc\Framework\Core\Config;
use Arc\Framework\Core\Container;
use Arc\Framework\Logging\Logger;
use Arc\Framework\Logging\LogLevel;
use Psr\Log\LoggerInterface;

class LoggingServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container): void
    {
        $container->singleton(LoggerInterface::class, function (Container $container) {
            $config = $container->get(Config::class);

            return new Logger($config->get('logging.path'), LogLevel::from($config->get('logging.min_level')));
        });
    }

    public function boot(Container $container): void
    {

    }
}
