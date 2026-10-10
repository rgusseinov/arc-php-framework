<?php

use Arc\Framework\Console\ConsoleApplication;
use Arc\Framework\Console\HelpCommand;
use Arc\Framework\Core\Config;

$app = require __DIR__ . '/../bootstrap/app.php';

$container = $app->getContainer();

$console = $container->get(ConsoleApplication::class);

$config = new Config(__DIR__ . '/../config');
$commands = $config->get('console.commands');

$helpCommand = null;
foreach ($commands as $command){
	$object = $container->get($command);

	if ($object instanceof HelpCommand){
		$helpCommand = $object;
	}

	$console->add($object);
}

if ($helpCommand instanceof HelpCommand){
	$helpCommand->setCommands($console::getCommands());
}

return $console;