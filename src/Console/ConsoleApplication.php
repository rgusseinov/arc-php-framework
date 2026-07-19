<?php

class ConsoleApplication {
	protected array $commands = [];


/* 	[
    'serve' => ServeCommand,
    'migrate' => MigrateCommand,
    'make:model' => MakeModelCommand,
]

	php arc make:model User

	the run() method must:

	Read $argv.
	Extract the command name.
	Find the corresponding command.
	Execute it.

	$argv = [
			'arc',         // $argv[0]
			'make:model',  // $argv[1]
			'User'         // $argv[2]
	];

 */
	public function add(CommandInterface $command): void {
	}

	public function run(array $argv): int {
		$command = $argv[1];			// make:model
		// $argument1 = $argv[3];			// --fresh
		// $argument2 = $argv[3];			// --fresh
		
		if (!array_key_exists($command, $this->commands)){
			throw new Exception("Command {$command} not found.");
		}

		$commandObject = $this->commands[$command];

		$result = $commandObject->execute(array_slice($argv, 2));

		return $result;		
	}
}