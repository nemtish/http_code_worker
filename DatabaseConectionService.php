<?php

class DatabaseConectionService
{
	private static $db = null;
	private static $instance = null;

	private function __construct($config) {
		$dsn = "mysql:host={$config['DB_HOST']};dbname={$config['DB_DATABASE']};port={$config['DB_PORT']}";
		$username = $config['DB_USERNAME'];
		$password = $config['DB_PASSWORD'];

		try {
			self::$db = new PDO($dsn, $username, $password);
		} catch (Exception $e) {
			die('DB connection error: '. $e->getMessage());
		}

	}

	public static function connect($config)
	{
		if (self::$instance === null) {
			self::$instance = new DatabaseConectionService($config);
		}
		return self::$db;
	}

	/* public static function getConnection() */
	/* { */
	/* 	return $this->db; */
	/* } */
}
