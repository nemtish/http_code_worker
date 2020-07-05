<?php
require('CurlHelper.php');

class DatabaseWorker {
	private const STATUS_NEW = 'NEW';
	private const STATUS_PROCESSING = 'PROCESSING';
	private const STATUS_DONE = 'DONE';
	private $dbConection;

	public function connectToDatabase($dbConfig)
	{
		$this->dbConection = DatabaseConectionService::connect($dbConfig);
	}

	public function run()
	{
		while($job = $this->getNextjob()) {
			$this->updateJob($job['id'], ['status' => self::STATUS_PROCESSING ]);
			$httpCode = CurlHelper::checkHttpCode($job['url']);

			echo "current job: {$job['id']} \n";
			echo "job http code: {$httpCode} \n";

			$this->updateJob($job['id'], [
				'status'	  => self::STATUS_DONE,
				'http_code' => $httpCode
			]);
		}
	}

	private function getNextjob()
	{
		$statement = $this->dbConection->prepare("SELECT id,url FROM jobs WHERE status=:status");
		$statement->execute(['status' => self::STATUS_NEW]);
		return $statement->fetch();
	}

	private function updateJob(int $id, array $newData)
	{
		$updateStatement = "UPDATE jobs SET ";
		foreach ($newData as $key => $value) {
			$updateStatement .= "{$key}=:{$key},";
		}
		// remove comma from the end of the statement
		$updateStatement = substr($updateStatement, 0, -1);
		$updateStatement .= " WHERE id={$id}";

		$statement = $this->dbConection->prepare($updateStatement);
		return $statement->execute($newData);
	}
}
