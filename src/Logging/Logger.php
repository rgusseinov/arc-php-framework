<?php

enum LogLevel: string {
  case Info = 'INFO';
  case Warning = 'WARNING';
  case Error = 'ERROR';
}

class Logger {
  private string $path;
  private string $mode = 'PRODUCTION';

  public function __construct(string $path){
    $this->path = $path;
  }

  private function writeLog(LogLevel $level, string $message): void {
    $date = date('Y-m-d H:i:s');

    $formattedMessage = "[$level->value][$date] " . $message . PHP_EOL;

    $baseName = dirname($this->path);

    if ($baseName !== '.' && !is_dir($baseName)){
      mkdir($baseName, 0777, true);
    }

    file_put_contents($this->path, $formattedMessage, FILE_APPEND);
  }

  public function info(string $message): void {
    if ($this->mode == 'PRODUCTION'){
      return;
    }

    $this->writeLog(LogLevel::Info, $message);
  }

  public function warning(string $message): void {
    $this->writeLog(LogLevel::Warning, $message);
  }

  public function error(string $message): void {
    $this->writeLog(LogLevel::Error, $message);
  }

}

$l = new Logger('storage/log.log');

$l->info('New user created');
$l->warning('New user not created');