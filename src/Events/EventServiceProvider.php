<?php

namespace Arc\Framework\Events;

use Arc\Framework\Providers\ServiceProviderInterface;
use Arc\Framework\Core\Container;
use Arc\Framework\Events\Listeners\SendEmailListener;
use Arc\Framework\Events\Listeners\LogListener;

class EventServiceProvider implements ServiceProviderInterface {
    /**
     * @var array<class-string<Event>, list<class-string<ListenerInterface>>>
     */
    protected array $listen = [
        OrderCreated::class => [
            SendEmailListener::class,
            LogListener::class,
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