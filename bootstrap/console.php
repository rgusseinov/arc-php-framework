<?php

$app = new ConsoleApplication();
$app->add(new ServeCommand());

$help = new HelpCommand();
$app->add($help);
$help->setCommands(ConsoleApplication::getCommands());

$app->add(new MakeControllerCommand());
$app->add(new MakeModelCommand());
return $app;