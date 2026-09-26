<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once 'Env.php';

use Arc\Framework\Database\Connection;
use Arc\Framework\Database\MigrationRunner;

Env::load(__DIR__ . '/.env');

$connection = new Connection(
		"mysql:host=" . Env::get('DB_HOST') .
		";dbname=" . Env::get('DB_NAME'),
		Env::get('DB_USER'),
		Env::get('DB_PASS')
);

$m = new MigrationRunner($connection);
$m->run();