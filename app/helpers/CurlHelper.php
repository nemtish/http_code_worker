<?php

namespace App\Helpers;

use Exception;

class CurlHelper {

	public static function checkUrlHttpCode(string $url) : int
	{
		$httpCode = self::checkHttpCode($url);
		if ($httpCode < 100) throw new Exception();

		return $httpCode;
	}

	private static function checkHttpCode(string $url) : int
	{
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_NOBODY, true);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_TIMEOUT,10);
		curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		return $httpCode;
	}
}
