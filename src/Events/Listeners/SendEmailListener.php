<?php

namespace Arc\Framework\Events\Listeners;

use Arc\Framework\Events\ListenerInterface;
use Arc\Framework\Events\Event;

class SendEmailListener implements ListenerInterface {
  public function handle(Event $event): void {
    // echo "Sent email notification</br>" . PHP_EOL;
  }
}