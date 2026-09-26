<?php

use Arc\Framework\Console\ConsoleApplication;
use Arc\Framework\Console\HelpCommand;
use Arc\Framework\Console\MakeControllerCommand;
use Arc\Framework\Console\MakeModelCommand;
use Arc\Framework\Console\ServeCommand;

$app = new ConsoleApplication();
$app->add(new ServeCommand());

$help = new HelpCommand();
$app->add($help);
$help->setCommands(ConsoleApplication::getCommands());

$app->add(new MakeControllerCommand());
$app->add(new MakeModelCommand());
return $app;