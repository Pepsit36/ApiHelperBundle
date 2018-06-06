<?php

namespace Pepsit36\ApiHelperBundle\Normalizer;

use Symfony\Component\HttpKernel\Exception\HttpException;

class HttpExceptionNormalizer extends AbstractNormalizer
{
	public function getExceptionsType()
	{
		return [HttpException::class];
	}

	public function acceptExceptionsInherited()
	{
		return true;
	}

	public function normalize(\Exception $exception)
	{
		return $this->generateData($exception->getStatusCode(), $exception->getMessage());
	}
}