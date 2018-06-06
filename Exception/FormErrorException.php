<?php

namespace Pepsit36\ApiHelperBundle\Exception;

use Symfony\Component\Form\Form;
use Throwable;

class FormErrorException extends \Exception
{
	/**
	 * @var Form
	 */
	private $form;

	public function __construct(Form $form, $message = "", $code = 0, Throwable $previous = null)
	{
		parent::__construct($message, $code, $previous);
		$this->form = $form;
	}

	/**
	 * @return Form
	 */
	public function getForm(): Form
	{
		return $this->form;
	}
}