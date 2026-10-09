<?php

use Arc\Framework\Console\ConsoleApplication;
use Arc\Framework\Console\HelpCommand;
use Arc\Framework\Console\MakeControllerCommand;
use Arc\Framework\Console\MakeModelCommand;
use Arc\Framework\Console\ServeCommand;

$app = new ConsoleApplication();
$help = new HelpCommand();

$app->add(new ServeCommand());
$app->add($help);
$app->add(new MakeControllerCommand());
$app->add(new MakeModelCommand());

$help->setCommands(ConsoleApplication::getCommands());

return $app;