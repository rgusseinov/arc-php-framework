<?php

class Application {
  private Container $container;
  private array $providers = [];
  private bool $booted = false;

  public function __construct(?Container $container = null){
    $this->container = $container ?? new Container();
  }

  public function register(ServiceProviderInterface $provider): void {
    if ($this->booted){
      throw new RuntimeException("Application already booted");
    }

    $this->providers[] = $provider;
  }

  public function boot(): void {
    foreach ($this->providers as $provider){
      $provider->register($this->container);
    }

    foreach ($this->providers as $provider){
      $provider->boot($this->container);
    }

    $this->booted = true;
  }
}