<?php

declare(strict_types=1);

namespace Rgusseinov\Arc\Logging;

class Logger {
  private string $path;
  private LogLevel $minLevel = LogLevel::Info;

  public function __construct(string $path, LogLevel $minLevel = LogLevel::Info){
    $this->path = $path;
    $this->minLevel = $minLevel;
  }

  private function writeLog(string $message, LogLevel $level = LogLevel::Info): void {
    if ($level->getPriority() < $this->minLevel->getPriority()){
      return;
    }

    $date = date('Y-m-d H:i:s');
    $formattedMessage = "[$level->value][$date] " . $message . PHP_EOL;

    $baseName = dirname($this->path);

    if ($baseName !== '.' && !is_dir($baseName)){
      mkdir($baseName, 0777, true);
    }

    file_put_contents($this->path, $formattedMessage, FILE_APPEND);
  }

  public function info(string $message): void {
    $this->writeLog($message, LogLevel::Info);
  }

  public function warning(string $message): void {
    $this->writeLog($message, LogLevel::Warning);
  }

  public function error(string $message): void {
    $this->writeLog($message, LogLevel::Error);
  }

}