<?php

use Arc\Framework\Core\Env;

return [
	'host' => Env::get('DB_HOST'),
	'name' => Env::get('DB_NAME'),
	'user' => Env::get('DB_USER'),
	'password' => Env::get('DB_PASS')
];