<?php

namespace App;

use App\Repositories\JobsRepository;
use App\Services\DatabaseWorker;
use Exception;

class HttpCodeWorker
{
	private $jobsRepository;

	public function __construct()
	{
		$this->jobsRepository = new JobsRepository();
	}

	public function init()
	{
		$w1 = new DatabaseWorker($this->jobsRepository);
		$w2 = new DatabaseWorker($this->jobsRepository);
		try {
			while (true) {
				$w1->run();
				$w2->run();
			}
		} catch (Exception $e) {
			die($e->getMessage());
		}
	}
}

