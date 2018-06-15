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
		$responseData = $this->normalizeData($data, $groups);

		return new JsonResponse(
			$responseData,
			$status
		);
	}

	protected function normalizeData($data, array $groups = ['default']): array
	{
		return $this->serializer->normalize(
			$data,
			null,
			['groups' => $groups]
		);
	}

}