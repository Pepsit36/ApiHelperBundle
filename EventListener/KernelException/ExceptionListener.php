<?php

namespace Pepsit36\ApiHelperBundle\EventListener\KernelException;

use Pepsit36\ApiHelperBundle\Normalizer\NormalizerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\ExceptionDataCollector;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\EventListener\ExceptionListener as BaseExceptionListener;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Translation\TranslatorInterface;

class ExceptionListener extends BaseExceptionListener
{

	/**
	 * @var LoggerInterface
	 */
	protected $logger;
	/**
	 * @var SerializerInterface
	 */
	private $serializer;
	/**
	 * @var array(NormalizerInterface)
	 */
	private $normalizers;
	/**
	 * @var TranslatorInterface
	 */
	private $translator;
	/**
	 * @var ExceptionDataCollector
	 */
	private $exceptionDataCollector;

	public function __construct(SerializerInterface $serializer, TranslatorInterface $translator, LoggerInterface $logger, ExceptionDataCollector $exceptionDataCollector = null)
	{
		$this->serializer = $serializer;
		$this->translator = $translator;
		$this->logger = $logger;
		$this->exceptionDataCollector = $exceptionDataCollector;
		$this->normalizers = [];
	}

	public function onKernelException(GetResponseForExceptionEvent $event)
	{
		if (preg_match('/^\/_/', $event->getRequest()->getPathInfo()) === 1) {
			return;
		}

		$result = null;
		$exception = $event->getException();

		foreach ($this->normalizers as $normalizer) {
			if ($normalizer->supports($exception)) {
				$result = $normalizer->normalize($exception);
				if (isset($result['domain'])) {
					$result['body']['message'] = $this->translator->trans($result['body']['message'], [], $result['domain']);
				}

				break;
			}
		}

		if (null == $result) {
			$result = [
				'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
				'body' => [
					'code'    => Response::HTTP_INTERNAL_SERVER_ERROR,
					'message' => 'Internal Error',
				],
			];
		}

		$event->setResponse(new JsonResponse($result['body'], $result['code']));

		if ($this->exceptionDataCollector !== null) {
			if ($result['code'] === Response::HTTP_INTERNAL_SERVER_ERROR) {
				$this->exceptionDataCollector->collect($event->getRequest(), $event->getResponse(), $event->getException());
				$this->logException($exception, sprintf('Uncaught PHP Exception %s: "%s" at %s line %s', get_class($exception), $exception->getMessage(), $exception->getFile(), $exception->getLine()));
			}
		}
	}

	public function addNormalizer(NormalizerInterface $normalizer)
	{
		$this->normalizers[] = $normalizer;
	}
}