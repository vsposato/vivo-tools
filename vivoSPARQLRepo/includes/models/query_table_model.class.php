<?php
	// require the sqllite class
	require_once('SQLite.php');

	// include the constants
	require_once('/')
	class queryTableModel {

		public $tableName = "query_table";

		public $tableDefinition = array(
			'id' => 'INTEGER PRIMARY KEY',
			'sparqlQuery' => 'TEXT NOT NULL',
			'shortDecription' => 'TEXT NOT NULL',
			'longDescription' => 'TEXT NULL',
			'owner' => 'INTEGER NOT NULL',
			'date_created' => 'TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP',
			'date_modified' => 'TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP',
		);

		protected $dbHandle;

		public function __construct() {

			// Instantiate the new database connection
			$this->dbHandle = new SQLite($dbPath);

			if (! $this->dbHandle) {

			}
		}

		public function __destruct() {

		}
	}


?>