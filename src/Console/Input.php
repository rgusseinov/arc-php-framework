<?php

namespace Arc\Framework\Console;

class Input {
		/** @var array<int, string> */
		private array $arguments = [];

		/** @var array<string, string|bool> */
		private array $options = [];

		/**
		 * @param array<int, string> $arguments
		 * @param array<string, string|bool> $options
		 */
		public function __construct(array $arguments, array $options){
			$this->arguments = $arguments;
			$this->options = $options;
		}

    public function hasOption(string $name): bool {
			return array_key_exists($name, $this->options);
    }

    public function getOption(string $name, mixed $default = null): mixed {
			return $this->options[$name] ?? $default;
    }

		/**
		 * @return array<string, string|bool>
		 */
		public function getOptions(): array {
			return $this->options;
    }

    public function hasArgument(int $index): bool {
			return array_key_exists($index, $this->arguments);
    }

    public function getArgument(int $index, mixed $default = null): mixed {
			return $this->arguments[$index] ?? $default;
    }

		/**
		 * @return array<int, string>
		 */
		public function getArguments(): array {
			return $this->arguments;
    }
}