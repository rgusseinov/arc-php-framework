<?php

declare(strict_types=1);

namespace Arc\Framework\Logging;
use Psr\Log\LoggerInterface;

class Logger implements LoggerInterface {
  private string $path;
  private LogLevel $minLevel = LogLevel::Info;

  public function __construct(string $path, LogLevel $minLevel = LogLevel::Info){
    $this->path = $path;
    $this->minLevel = $minLevel;
  }

  private function writeLog(string|\Stringable $message, LogLevel $level = LogLevel::Info): void {
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

  public function info(string|\Stringable $message, array $context = []): void {
    $this->writeLog($message, LogLevel::Info);
  }

  public function warning(string|\Stringable $message, array $context = []): void {
    $this->writeLog($message, LogLevel::Warning);
  }

  public function error(string|\Stringable $message, array $context = []): void {
    $this->writeLog($message, LogLevel::Error);
  }

  public function debug(string|\Stringable $message, array $context = []): void {
    $this->writeLog($message, LogLevel::Debug);
  }

  public function notice(string|\Stringable $message, array $context = []): void {
    $this->writeLog($message, LogLevel::Notice);
  }

  public function critical(string|\Stringable $message, array $context = []): void {
    $this->writeLog($message, LogLevel::Critical);
  }

  public function alert(string|\Stringable $message, array $context = []): void {
    $this->writeLog($message, LogLevel::Alert);
  }

  public function emergency(string|\Stringable $message, array $context = []): void {
    $this->writeLog($message, LogLevel::Emergency);
  }

  public function log($level, string|\Stringable $message, array $context = []): void {
    $logLevel = LogLevel::tryFrom($level);

    if ($logLevel === null) {
        throw new \Psr\Log\InvalidArgumentException("Unknown log level: {$level}");
    }

    $this->writeLog($message, $logLevel);
  }
}