<?php

namespace Pepsit36\ApiHelperBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class ApiController extends Controller
{
	protected $serializer;

	public function __construct(SerializerInterface $serializer)
	{
		$this->serializer = $serializer;
	}

	protected function createJsonResponse($data, array $groups = ['default'], int $status = 200): JsonResponse
	{
		$responseData = $this->serializeData($data, $groups);

		return new JsonResponse(
			$responseData,
			$status,
			[],
			true
		);
	}

	protected function serializeData($data, array $groups = ['default']): string
	{
		return $this->serializer->serialize(
			$data,
			'json',
			['groups' => $groups]
		);
	}

}