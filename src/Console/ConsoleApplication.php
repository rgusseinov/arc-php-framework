<?php

class ConsoleApplication {
	protected static array $commands = [];

	public function add(CommandInterface $command): void {
		self::$commands[$command->getName()] = $command;
	}

	public function run(array $argv): int {
		$command = $argv[1];

		if (!array_key_exists($command, self::$commands)){
			throw new Exception("Command {$command} not found.");
		}

 		$parser = new ArgvParser();
		$input = $parser->parse($argv);

		$commandObject = self::$commands[$command];

		$result = $commandObject->execute($input);

		return $result;		
	}

	public static function getCommands(){
		return self::$commands;
	}
}