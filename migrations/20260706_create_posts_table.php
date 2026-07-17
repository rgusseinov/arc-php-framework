<?php

require_once './src/Database/Migration.php';

return new class extends Migration {
    public function up(): void {
         $this->connection->execute("CREATE TABLE posts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            title VARCHAR(255) NOT NULL,
            body TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)");
    }

    public function down(): void {
        $this->connection->execute("DROP TABLE IF EXISTS posts");
    }
};