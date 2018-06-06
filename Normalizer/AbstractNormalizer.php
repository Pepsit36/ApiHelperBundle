<?php

namespace Pepsit36\ApiHelperBundle\Normalizer;

abstract class AbstractNormalizer implements NormalizerInterface
{
	public function acceptExceptionsInherited()
	{
		return false;
	}

	public function supports(\Exception $exception)
	{
		if ($this->acceptExceptionsInherited()) {
			foreach ($this->getExceptionsType() as $exceptionType) {
				if ($exception instanceof $exceptionType) {
					return true;
				}
			}

			return false;
		} else {
			return in_array(get_class($exception), $this->getExceptionsType());
		}
	}

	public function generateData($code, $message, $domain = null, $data = null)
	{
		$result = [
			'code' => $code,
			'body' => [
				'code'    => $code,
				'message' => $message,
			],
		];

		if ($data !== null) {
			$result['body']['data'] = $data;
		}

		return $result;
	}
}