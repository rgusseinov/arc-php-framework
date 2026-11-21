<?php

namespace Arc\Framework\Console;

use Throwable;

class ConsoleApplication
{
    /**
     * @var array<string, CommandInterface>
     */
    private array $commands = [];

    public function add(CommandInterface $command): void
    {
        $this->commands[$command->getName()] = $command;
    }

    /**
     * @param array<string> $argv
     */
    public function run(array $argv): int
    {
        if (!isset($argv[1])) {
            return $this->showHelp();
        }

        $command = $argv[1];

        if (!array_key_exists($command, $this->commands)) {
            fwrite(STDERR, "Command {$command} not found." . PHP_EOL);

            $this->showHelp();

            return 1;
        }

        $parser = new ArgvParser();
        $input = $parser->parse(array_slice($argv, 2));

        $commandObject = $this->commands[$command];

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
    public function getCommands(): array
    {
        return $this->commands;
    }

    private function showHelp(): int
    {
        if (!isset($this->commands['help'])) {
            fwrite(STDERR, "Help command is not registered." . PHP_EOL);

            return 1;
        }

        return $this->commands['help']->execute(new Input([], []));
    }
}
