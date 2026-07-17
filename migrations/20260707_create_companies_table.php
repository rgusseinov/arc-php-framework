<?php

require_once './src/Database/Migration.php';

return new class extends Migration {
    public function up(): void {
        $this->connection->execute("
            CREATE TABLE companies (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ");
    }

    public function down(): void {
        $this->connection->execute("
            DROP TABLE IF EXISTS companies
        ");
    }
};