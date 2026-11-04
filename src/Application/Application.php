<?php

namespace Arc\Framework\Application;

use Arc\Framework\Core\Container;
use Arc\Framework\Providers\ServiceProviderInterface;
use RuntimeException;

class Application
{
    private Container $container;
    private string $basePath;
    /** @var list<ServiceProviderInterface> */
    private array $providers = [];
    private bool $booted = false;

    public function __construct(string $basePath, ?Container $container = null)
    {
        $this->basePath = rtrim($basePath, '/');
        $this->container = $container ?? new Container();

        // Application доступен через контейнер (autowiring по типу)
        $this->container->singleton(self::class, fn () => $this);
    }

    public function basePath(string $path = ''): string
    {
        return $path === '' ? $this->basePath : $this->basePath . '/' . ltrim($path, '/');
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
