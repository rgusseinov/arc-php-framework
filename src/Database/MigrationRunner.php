<?php

/*
	найти все файлы миграций;
	создать объекты миграций;
	вызвать up();
	отметить их как выполненные в таблице migrations.
*/

class MigrationRunner {
    private Connection $connection;
    private string $migrationPath;

		public function __construct(Connection $connection){
			$this->connection = $connection;
			$this->migrationPath = __DIR__ . '/../../migrations';
		}

    public function run(): void {
			$this->createMigrationsTableIfNotExists();

			$files = $this->findMigrationFiles();
			$executedMigrations = $this->getExecutedMigrations();

			foreach ($files as $file) {
					$migrationName = basename($file);

					if (in_array($migrationName, $executedMigrations, true)) {
						echo "Skipped {$migrationName}\n";
						continue;
					}

					$migration = require $file;

					if (!$migration instanceof Migration){
						throw new Exception("Migration file {$migrationName} must be Migration instance");
					}

					$migration->setConnection($this->connection);

					$migration->up();

					$this->markAsExecuted($migrationName);

					echo "Migrated: {$migrationName}\n";
			}
    }

    private function createMigrationsTableIfNotExists(): void {
        $this->connection->execute("
            CREATE TABLE IF NOT EXISTS migrations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255) NOT NULL UNIQUE,
                executed_at DATETIME NOT NULL
            )
        ");
    }

		private function findMigrationFiles(): array {
			$files = glob($this->migrationPath . '/*.php');

			sort($files);

			return $files;
		}
		
		private function getExecutedMigrations(): array {
			$rows = $this->connection->fetchAll('SELECT migration FROM migrations');

			return array_column($rows, 'migration');
		}

		private function markAsExecuted(string $migrationName): void {
			$this->connection->execute('INSERT INTO migrations(migration, executed_at) VALUES (?, NOW())', [$migrationName]);
		}
}