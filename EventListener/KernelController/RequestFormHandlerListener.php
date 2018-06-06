<?php

namespace Pepsit36\ApiHelperBundle\EventListener\KernelController;

use Doctrine\Common\Annotations\Reader;
use Pepsit36\ApiHelperBundle\Annotation\RequestFormHandler;
use Pepsit36\ApiHelperBundle\Exception\FormErrorException;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class RequestFormHandlerListener
{
	/**
	 * @var Reader
	 */
	private $reader;
	/**
	 * @var FormFactoryInterface
	 */
	private $formFactory;

	public function __construct(Reader $reader, FormFactoryInterface $formFactory)
	{
		$this->reader = $reader;
		$this->formFactory = $formFactory;
	}

	public function onKernelController(FilterControllerEvent $event)
	{
		if (!is_array($controllers = $event->getController())) {
			return;
		}

		$request = $event->getRequest();

		list($controller, $methodName) = $controllers;

		$reflectionObject = new \ReflectionObject($controller);
		$reflectionMethod = $reflectionObject->getMethod($methodName);
		$methodAnnotation = $this->reader
			->getMethodAnnotation($reflectionMethod, RequestFormHandler::class);

		if (!$methodAnnotation) {
			return;
		}

		$form = $this->formFactory->create($methodAnnotation->getFormType());

		$dataClass = $form->getConfig()->getDataClass();
		if ($dataClass !== null) {
			$form->setData(new $dataClass());
		}

		switch ($methodAnnotation->getFrom()) {
			case 'content':
				$form->submit(json_decode($request->getContent(), true));
				break;
			case 'query':
				$form->submit($request->query->all());
				break;
			case 'request':
				$form->handleRequest($request);
				break;
		}

		if (!($form->isSubmitted() && $form->isValid())) {
			throw new FormErrorException($form);
		}

		$request->attributes->set($methodAnnotation->getParamName(), $form->getData());
	}
}