<?php

class EventDispatcher
{
    /**
     * @var array<string, ListenerInterface[]>
     */
    private array $listeners = [];

    public function listen(string $event, ListenerInterface $listener): void
    {
        $this->listeners[$event][] = $listener;
    }

    public function dispatch(Event $event): void
    {
        $eventName = get_class($event);

        $listeners = $this->listeners[$eventName] ?? [];

        foreach ($listeners as $listener) {
            $listener->handle($event);
        }
    }
}