<?php

$app = new ConsoleApplication();
$app->add(new ServeCommand());

$help = new HelpCommand();

$app->add($help);
$help->setCommands(ConsoleApplication::getCommands());

return $app;