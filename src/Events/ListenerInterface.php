<?php

interface ListenerInterface
{
    public function handle(Event $event): void;
}