<?php

namespace App\Services;

use App\Helpers\CurlHelper;
use App\Repositories\JobsRepository;
use Exception;

class DatabaseWorker {

	private static $index = 1;
	private $workerId;
	private $jobsRepository;

	public function __construct(JobsRepository $jobsRepository)
	{
		$this->jobsRepository = $jobsRepository;
		$this->workerId = 'ID'.static::$index;
		static::$index++;
	}

	public function run()
	{
		$job = $this->jobsRepository->getFirstNewJob();

		if ($job) {
			$this->lockCurrentJob($job);
			$httpCode = $this->getJobResult($job);

			echo "WORKER {$this->workerId} \n";
			echo "current job: {$job['url']} \n";
			echo "job http code: {$httpCode} \n";

			$this->jobsRepository->updateJob($job['id'], [
				'status'	=> JobsRepository::STATUS_DONE,
				'http_code' => $httpCode
			]);
		} else {
			throw new Exception('no more jobs');
		}
	}

	/**
	 * Lock current worker job
	 * so multiple workers can be run
	 */
	private function lockCurrentJob(array $job)
	{
		$this->currentJob = $job;
		$this->jobsRepository->updateJob(
			$job['id'],
			['status' => JobsRepository::STATUS_PROCESSING ]
		);
	}

	/**
	 * Return job result http code
	 */
	private function getJobResult(array $job)
	{
		return CurlHelper::checkHttpCode($job['url']);
	}
}
