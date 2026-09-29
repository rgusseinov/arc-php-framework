<?php

require_once __DIR__ . '/vendor/autoload.php';

use Arc\Framework\Logging\Logger;
use Arc\Framework\Logging\LogLevel;

$logger = new Logger('storage/log.log', LogLevel::Warning);


$logger->debug('This should be filtered out');
$logger->info('This should also be filtered out');
$logger->warning('This should appear');
$logger->error('This should appear');
$logger->log('critical', 'The user with {id} is not exists', ['id' => 25]);

/* try {
    $logger->log('not_a_real_levl', 'This should throw');
} catch (\Psr\Log\InvalidArgumentException $e) {
    echo "Caught expected exception: " . $e->getMessage() . PHP_EOL;
} */

$obj = new class implements \Stringable {
    public function __toString(): string { return 'OBJ_VALUE'; }
};

$logger->warning('Test with object: {val}', ['val' => $obj]);