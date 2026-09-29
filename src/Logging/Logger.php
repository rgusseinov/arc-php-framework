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

  private function writeLog(string|\Stringable $message, array $context = [], LogLevel $level = LogLevel::Info): void {
      if ($level->getPriority() < $this->minLevel->getPriority()){
          return;
      }

      $interpolatedMessage = $this->interpolate($message, $context);

      $date = date('Y-m-d H:i:s');
      $formattedMessage = "[$level->value][$date] " . $interpolatedMessage . PHP_EOL;

      $baseName = dirname($this->path);

      if ($baseName !== '.' && !is_dir($baseName)){
          mkdir($baseName, 0777, true);
      }

      file_put_contents($this->path, $formattedMessage, FILE_APPEND | LOCK_EX);
  }

  private function interpolate(string|\Stringable $message, array $context = []): string {
    // build a replacement array with braces around the context keys
    $replace = array();
    foreach ($context as $key => $val) {
        // check that the value can be cast to string
        if (!is_array($val) && (!is_object($val) || method_exists($val, '__toString'))) {
            $replace['{' . $key . '}'] = $val;
        }
    }

    // interpolate replacement values into the message and return
    return strtr((string) $message, $replace);
  }

  public function info(string|\Stringable $message, array $context = []): void {
    $this->writeLog($message, $context, LogLevel::Info);
  }

  public function warning(string|\Stringable $message, array $context = []): void {
    $this->writeLog($message, $context, LogLevel::Warning);
  }

  public function error(string|\Stringable $message, array $context = []): void {
    $this->writeLog($message, $context, LogLevel::Error);
  }

  public function debug(string|\Stringable $message, array $context = []): void {
    $this->writeLog($message, $context, LogLevel::Debug);
  }

  public function notice(string|\Stringable $message, array $context = []): void {
    $this->writeLog($message, $context, LogLevel::Notice);
  }

  public function critical(string|\Stringable $message, array $context = []): void {
    $this->writeLog($message, $context, LogLevel::Critical);
  }

  public function alert(string|\Stringable $message, array $context = []): void {
    $this->writeLog($message, $context, LogLevel::Alert);
  }

  public function emergency(string|\Stringable $message, array $context = []): void {
    $this->writeLog($message, $context, LogLevel::Emergency);
  }

  public function log($level, string|\Stringable $message, array $context = []): void {
    $logLevel = LogLevel::tryFrom($level);

    if ($logLevel === null) {
        throw new \Psr\Log\InvalidArgumentException("Unknown log level: {$level}");
    }

    $this->writeLog($message, $context, $logLevel);
  }
}