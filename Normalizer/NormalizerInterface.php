<?php

namespace Pepsit36\ApiHelperBundle\Normalizer;

interface NormalizerInterface
{
	public function getExceptionsType();

	public function acceptExceptionsInherited();

	public function normalize(\Exception $exception);

	public function supports(\Exception $exception);
}