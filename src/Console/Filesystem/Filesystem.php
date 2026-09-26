<?php

namespace Arc\Framework\Console\Filesystem;

class Filesystem {
	public function exists(string $path): bool {
		return file_exists($path);
	}

	public function createDirectory(string $path): void {
		mkdir($path);
	}

	public function write(string $path, string $content): void {
		file_put_contents($path, $content);
	}

}