<?php

require_once __DIR__ . '/vendor/autoload.php';

use Arc\Framework\Logging\LogLevel;

var_dump(LogLevel::Info);

use Arc\Framework\Logging\Logger;

$logger = new Logger('storage/log.log');

$logger->info('This is test');