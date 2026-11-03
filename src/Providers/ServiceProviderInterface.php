<?php

namespace Arc\Framework\Providers;

use Arc\Framework\Core\Container;

interface ServiceProviderInterface
{
    public function register(Container $container): void;

    public function boot(Container $container): void;
}
