<?php

require_once './src/Database/Migration.php';

return new class extends Migration {
    public function up(): void {
        $this->connection->execute("
            CREATE TABLE employees (
                id INT AUTO_INCREMENT PRIMARY KEY,
                company_id INT NOT NULL,
                full_name VARCHAR(255) NOT NULL,
                position VARCHAR(100) NOT NULL,
                salary DECIMAL(10,2) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

                CONSTRAINT fk_employees_company
                    FOREIGN KEY (company_id)
                    REFERENCES companies(id)
                    ON DELETE CASCADE
            )
        ");
    }

    public function down(): void {
        $this->connection->execute("
            DROP TABLE IF EXISTS employees
        ");
    }
};