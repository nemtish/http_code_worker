<?php

namespace App\Repositories;

class JobsRepository extends Repository
{
	const STATUS_NEW = 'NEW';
	const STATUS_PROCESSING = 'PROCESSING';
	const STATUS_DONE = 'DONE';
	const STATUS_ERROR = 'ERROR';


	public function getFirstNewJob()
	{
		return $this->dbConection->fetchAssoc("SELECT id,url FROM jobs WHERE status=?", [static::STATUS_NEW]);
	}


	public function updateJob(int $id, array $newData)
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
