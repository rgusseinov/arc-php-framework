#!/usr/bin/env php
<?php

require __DIR__ . '/vendor/autoload.php';
$console = require __DIR__ . '/bootstrap/console.php';

$argv = $_SERVER['argv'] ?? [];
$status = $console->run($argv);

exit($status);
