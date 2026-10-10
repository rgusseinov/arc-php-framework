<?php

use Arc\Framework\Console\HelpCommand;
use Arc\Framework\Console\MakeControllerCommand;
use Arc\Framework\Console\MakeModelCommand;
use Arc\Framework\Console\MigrateCommand;
use Arc\Framework\Console\ServeCommand;

return [
    'commands' => [
        'migrate' => MigrateCommand::class,
        'serve' => ServeCommand::class,
        'controller' => MakeControllerCommand::class,
        'model' => MakeModelCommand::class,
				'help' => HelpCommand::class
    ],
];