<?php

namespace App\Repositories;

use Exception;
use Symfony\Component\Dotenv\Dotenv;

class Repository
{
	protected $dbConection;

	public function __construct()
	{
		$dotenv = new Dotenv();
		$dotenv->load(__DIR__.'/../../.env');

		$connectionParams = array(
			'dbname' => $_ENV['DB_NAME'],
			'user' => $_ENV['DB_USERNAME'],
			'password' => $_ENV['DB_PASSWORD'],
			'host' => $_ENV['DB_HOST'],
			'driver' => $_ENV['DB_DRIVER'],
		);

		try {
			$this->dbConection = \Doctrine\DBAL\DriverManager::getConnection($connectionParams);
		} catch(Exception $e) {
			die('DB Connection error: '.$e->getMessage());
		}

	}
}
