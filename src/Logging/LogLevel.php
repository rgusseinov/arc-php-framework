<?php

declare(strict_types=1);

namespace Rgusseinov\Arc\Logging;

enum LogLevel: string {
  case Info = 'INFO';
  case Warning = 'WARNING';
  case Error = 'ERROR';

  public function getPriority(): int {
    return match ($this){
      self::Info => 0,
      self::Warning => 1,
      self::Error => 2
    };
  }
}
