<?php

enum LogLevel: string {
  case Info = 'INFO';
  case Warning = 'WARNING';
  case Fatal = 'FATAL';
}

class Logger {
  private string $path;

  public function __construct(string $path){
    $this->path = $path;
  }

  private function prepareLog(LogLevel $level, string $message): void {
    $date = date('Y-m-d H:i:s');

    $formattedMessage = "[$level->value][$date] " . $message . PHP_EOL;

    $baseName = dirname($this->path);

    if ($baseName !== '.' && !is_dir($baseName)){
      mkdir(dirname($this->path), 0777, true);
    }

    file_put_contents($this->path, $formattedMessage, FILE_APPEND);
  }

  public function log(string $message): void {
    $this->prepareLog(LogLevel::Info, $message);
  }
  
  public function info(string $message): void {
    $this->prepareLog(LogLevel::Info, $message);
  }

  public function warning(string $message): void {
    $this->prepareLog(LogLevel::Warning, $message);
  }

  public function fatal(string $message): void {
    $this->prepareLog(LogLevel::Fatal, $message);
  }
  


}

$l = new Logger('storage/log.log');

$l->log('New user created');
$l->fatal('New user not created');