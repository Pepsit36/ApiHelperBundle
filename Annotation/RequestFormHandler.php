<?php

namespace Pepsit36\ApiHelperBundle\Annotation;

use Doctrine\Common\Annotations;
use Symfony\Component\Form\AbstractType;

/**
 * Class RequestFormHandler
 *
 * @package App\RequestFormHandler
 *
 * @Annotations
 */
class RequestFormHandler
{
	/**
	 * @var string
	 */
	private $paramName;

	/**
	 * @var string
	 */
	private $formType;

	/**
	 * @var string
	 */
	private $from;

	/**
	 * @param array $data An array of key/value parameters
	 *
	 * @throws \BadMethodCallException
	 */
	public function __construct(array $data)
	{
		$this->from = 'content';

		if (isset($data['value'])) {
			$data['paramName'] = $data['value'];
			unset($data['value']);
		}

		foreach ($data as $key => $value) {
			if (!method_exists($this, $method = 'set' . ucfirst($key))) {
				throw new \BadMethodCallException(sprintf('Unknown property "%s" on annotation "%s".', $key, get_class($this)));
			}
			$this->$method($value);
		}
	}

	/**
	 * @return string
	 */
	public function getParamName(): string
	{
		return $this->paramName;
	}

	/**
	 * @param string $paramName
	 *
	 * @return RequestFormHandler
	 */
	public function setParamName(string $paramName): RequestFormHandler
	{
		$this->paramName = $paramName;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getFormType(): string
	{
		return $this->formType;
	}

	/**
	 * @param string $formType
	 *
	 * @return RequestFormHandler
	 */
	public function setFormType(string $formType): RequestFormHandler
	{
		if (!is_subclass_of($formType, AbstractType::class)) {
			throw new \BadMethodCallException(
				sprintf(
					'Bad value for property "formType" on annotation "%s": 
                must to be namespace of a class which inherit of "%s"',
					get_class($this),
					AbstractType::class
				)
			);
		}

		$this->formType = $formType;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getFrom(): string
	{
		return $this->from;
	}

	/**
	 * @param string $from
	 */
	public function setFrom(string $from): void
	{
		$this->from = $from;
	}

}