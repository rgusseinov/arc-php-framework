<?php

namespace Arc\Framework\Console;

use Arc\Framework\Console\ArgvParser;
use Arc\Framework\Console\CommandInterface;
use Throwable;

class ConsoleApplication {
	/**
	 * @var array<string, CommandInterface>
	 */
	protected static array $commands = [];

	public function add(CommandInterface $command): void {
		self::$commands[$command->getName()] = $command;
	}

	/**
	 * @param array<string> $argv
	 */
	public function run(array $argv): int {
		if (!isset($argv[1])){
			return self::$commands['help']->execute(new Input([], []));
		}

		$command = $argv[1];

		if (!array_key_exists($command, self::$commands)){
			fwrite(STDERR, "Command {$command} not found." . PHP_EOL);

			self::$commands['help']->execute(new Input([], []));

			return 1;
		}

 		$parser = new ArgvParser();
		$input = $parser->parse(array_slice($argv, 2));

		$commandObject = self::$commands[$command];

		try {
				return $commandObject->execute($input);
		} catch (Throwable $e) {
				fwrite(STDERR, "Error: " . $e->getMessage() . PHP_EOL);

				return 1;
		}
	}

	/**
	 * @return array<string, CommandInterface>
	 */
	public static function getCommands(): array {
		return self::$commands;
	}
}