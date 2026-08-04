<?php

class EventServiceProvider implements ServiceProviderInterface
{
    protected array $listen = [
        OrderCreated::class => [
            SendEmailListener::class,
            Logger::class,
        ],
    ];

    public function register(Container $container): void
    {
        $container->singleton(EventDispatcher::class);
    }

    public function boot(Container $container): void
    {
        $dispatcher = $container->get(EventDispatcher::class);

        foreach ($this->listen as $event => $listeners) {
            foreach ($listeners as $listener) {
                $dispatcher->listen(
                    $event,
                    $container->get($listener)
                );
            }
        }
    }
}