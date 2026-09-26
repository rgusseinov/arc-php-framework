<?php

namespace Arc\Framework\Events;

interface ListenerInterface
{
    public function handle(Event $event): void;
}