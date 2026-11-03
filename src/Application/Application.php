<?php

namespace Arc\Framework\Application;

use Arc\Framework\Core\Container;
use Arc\Framework\Providers\ServiceProviderInterface;
use RuntimeException;

class Application
{
    private Container $container;
    /** @var list<ServiceProviderInterface> */
    private array $providers = [];
    private bool $booted = false;

    public function __construct(?Container $container = null)
    {
        $this->container = $container ?? new Container();
    }

    public function register(ServiceProviderInterface $provider): void
    {
        if ($this->booted) {
            throw new RuntimeException('Application already booted.');
        }

        $this->providers[] = $provider;
    }

    public function boot(): void
    {
        if ($this->booted) {
            throw new RuntimeException('Application already booted.');
        }

        foreach ($this->providers as $provider) {
            $provider->register($this->container);
        }

        foreach ($this->providers as $provider) {
            $provider->boot($this->container);
        }

        $this->booted = true;
    }

    public function getContainer(): Container
    {
        return $this->container;
    }
}
