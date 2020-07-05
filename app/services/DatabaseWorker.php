<?php

namespace App\Services;

use App\Helpers\CurlHelper;
use App\Repositories\JobsRepository;

class DatabaseWorker {
	private $jobsRepository;

	public function __construct(JobsRepository $jobsRepository)
	{
		$this->jobsRepository = $jobsRepository;
	}

	public function run()
	{
		while($job = $this->jobsRepository->getNewJob()) {
			$this->lockCurrentJob($job);
			$httpCode = $this->getJobResult($job);

			echo "current job: {$job['id']} \n";
			echo "job http code: {$httpCode} \n";

			$this->jobsRepository->updateJob($job['id'], [
				'status'	=> JobsRepository::STATUS_DONE,
				'http_code' => $httpCode
			]);
		}
	}

	private function lockCurrentJob($job)
	{
		$this->jobsRepository->updateJob(
			$job['id'],
			['status' => JobsRepository::STATUS_PROCESSING ]
		);
	}

	private function getJobResult($job)
	{
		return CurlHelper::checkHttpCode($job['url']);
	}
}
