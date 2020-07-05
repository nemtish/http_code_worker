<?php

namespace App;

use App\Repositories\JobsRepository;
use App\Services\DatabaseWorker;

class HttpCodeWorker
{
	public function run()
	{
		$jobsRepository = new JobsRepository();
		$databaseWorker = new DatabaseWorker($jobsRepository);
		$databaseWorker->run();
	}
}

