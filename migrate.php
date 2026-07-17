<?php

require_once 'Env.php';
require_once './src/Database/Connection.php';
require_once './src/Database/MigrationRunner.php';

Env::load(__DIR__ . '/.env');

$connection = new Connection(
		"mysql:host=" . Env::get('DB_HOST') .
		";dbname=" . Env::get('DB_NAME'),
		Env::get('DB_USER'),
		Env::get('DB_PASS')
);

$m = new MigrationRunner($connection);
$m->run();