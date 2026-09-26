<?php

namespace Arc\Framework\Console;

use Arc\Framework\Console\ArgvParser;
use Arc\Framework\Console\CommandInterface;
use Exception;

class ConsoleApplication {
	protected static array $commands = [];

	public function add(CommandInterface $command): void {
		self::$commands[$command->getName()] = $command;
	}

	public function run(array $argv): int {
		if (!isset($argv[1])){
			throw new Exception("Incorrect command entered");
		}	

		$command = $argv[1];

		if (!array_key_exists($command, self::$commands)){
			throw new Exception("Command {$command} not found.");
		}

 		$parser = new ArgvParser();
		$input = $parser->parse(array_slice($argv, 2));

		$commandObject = self::$commands[$command];

		$result = $commandObject->execute($input);

		return $result;		
	}

	public static function getCommands(){
		return self::$commands;
	}
}