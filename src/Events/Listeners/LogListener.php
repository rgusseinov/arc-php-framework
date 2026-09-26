<?php

namespace Arc\Framework\Events\Listeners;

use Arc\Framework\Events\ListenerInterface;
use Arc\Framework\Events\Event;

class LogListener implements ListenerInterface {
  public function handle(Event $event): void {
    // echo 'Added logs</br>' . PHP_EOL;
  }
}