<?php

use Arc\Framework\Console\ConsoleApplication;
use Arc\Framework\Console\HelpCommand;
use Arc\Framework\Console\MakeControllerCommand;
use Arc\Framework\Console\MakeModelCommand;
use Arc\Framework\Console\MigrateCommand;
use Arc\Framework\Console\ServeCommand;

$app = require __DIR__ . '/../bootstrap/app.php';

$container = $app->getContainer();

$console = $container->get(ConsoleApplication::class);
$help = $container->get(HelpCommand::class);

$console->add($container->get(MigrateCommand::class));
$console->add($container->get(ServeCommand::class));
$console->add($help);
$console->add($container->get(MakeControllerCommand::class));
$console->add($container->get(MakeModelCommand::class));

$help->setCommands(ConsoleApplication::getCommands());

return $console;