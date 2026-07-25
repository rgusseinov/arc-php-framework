<?php

class MakeControllerCommand implements CommandInterface {
	public function getName(): string {
		return "make:controller";	
	}

	public function getDescription(): string {
		return "Create controller";
	}

	public function execute(Input $input): int {
		$controller = $input->getArgument(0);

		$path = "src/Controllers/";

		$filesystem = new Filesystem();
		if (!$filesystem->exists($path)){
			$filesystem->createDirectory($path);
		}

		$controllerFile = $path . $controller . '.php';

		if ($filesystem->exists($controllerFile)){
			echo "Controller {$controller} already exists.." . PHP_EOL;
			return 1;
		}

		$content = file_get_contents(__DIR__ . "/../../stubs/controller.stub");

		if ($content === false) {
			throw new RuntimeException("Stub not found.");
		}

		$template = '{{class}}';
		
		$replacement = str_replace($template, $controller, $content);

		$filesystem->write($controllerFile, $replacement);

		echo "Controller {$controller} created successfully. " . PHP_EOL;

		return 0;
	}
}

/*
MakeControllerCommand
        │
        ├── получает имя контроллера
        │
        ├── читает controller.stub
        │
        ├── подставляет {{class}}
        │
        ▼
Filesystem
        │
        ├── createDirectory()
        ├── exists()
        ├── write()
        └── ...
*/