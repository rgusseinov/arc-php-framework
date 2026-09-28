<?php

declare(strict_types=1);

namespace Arc\Framework\Logging;

enum LogLevel: string {
  case Info = 'info';
  case Warning = 'warning';
  case Emergency = 'emergency';
  case Alert = 'alert';
  case Critical = 'critical';
  case Error = 'error';
  case Notice = 'notice';
  case Debug = 'debug';

  public function getPriority(): int {
    return match ($this){
      self::Debug => 0,
      self::Info => 1,
      self::Notice => 2,
      self::Warning => 3,
      self::Error => 4,
      self::Critical => 5,
      self::Alert => 6,
      self::Emergency => 7
    };
  }
}
