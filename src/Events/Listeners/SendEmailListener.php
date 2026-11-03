<?php

namespace Arc\Framework\Events\Listeners;

use Arc\Framework\Events\ListenerInterface;
use Arc\Framework\Events\Event;
use Arc\Framework\Events\OrderCreated;
use Psr\Log\LoggerInterface;

class SendEmailListener implements ListenerInterface
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function handle(Event $event): void
    {
        if ($event instanceof OrderCreated) {
            $this->logger->info("Confirmation email for order {id} sent", ['id' => $event->getOrderId()]);
        }
    }
}
