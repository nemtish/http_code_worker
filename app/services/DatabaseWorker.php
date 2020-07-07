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
			$this->perform($job);
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
		$this->jobsRepository->updateJob(
			$job['id'],
			['status' => JobsRepository::STATUS_PROCESSING ]
		);
	}

	/**
	 * Perform job and write http code in DB
	 */
	private function perform(array $job)
	{
		try {
			$httpCode = CurlHelper::checkUrlHttpCode($job['url']);
			$status = JobsRepository::STATUS_DONE;
		} catch (Exception $e) {
			$status = JobsRepository::STATUS_ERROR;
			$httpCode = $e->getCode();
		}

		echo "WORKER {$this->workerId} \n";
		echo "current job: {$job['url']} \n";
		echo "job status: {$status} \n";
		echo "job http code: {$httpCode} \n";

		$this->jobsRepository->updateJob($job['id'], [
			'status'	=> $status,
			'http_code' => $httpCode
		]);
	}
}
