<?php

abstract class Migration {
	protected Connection $connection;

	public function setConnection(Connection $connection): void {
			$this->connection = $connection;
	}

	abstract public function up();

	abstract public function down();
	
}