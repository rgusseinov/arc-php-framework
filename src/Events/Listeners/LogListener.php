<?php

namespace Arc\Framework\Events\Listeners;

use Arc\Framework\Events\ListenerInterface;
use Arc\Framework\Events\Event;
use Arc\Framework\Events\OrderCreated;
use Psr\Log\LoggerInterface;

class LogListener implements ListenerInterface {
  private LoggerInterface $logger;

  public function __construct(LoggerInterface $logger){
    $this->logger = $logger;
  }

  public function handle(Event $event): void {
    if ($event instanceof OrderCreated){
      $this->logger->info("Order {id} created", ['id' => $event->getOrderId()]);
    }
  }
}