<?php

namespace Pepsit36\ApiHelperBundle\Normalizer;

use Pepsit36\ApiHelperBundle\Exception\FormErrorException;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;

class FormErrorExceptionNormalizer extends AbstractNormalizer
{
	public function getExceptionsType()
	{
		return [FormErrorException::class];
	}

	public function normalize(\Exception $exception)
	{
		return $this->generateData(
			Response::HTTP_BAD_REQUEST,
			$exception->getMessage(),
			null,
			$this->serializeErrors($exception->getForm())
		);
	}

	// https://knpuniversity.com/screencast/symfony-rest2/validation-errors-response
	// http://stackoverflow.com/questions/24556121/how-to-return-json-encoded-form-errors-in-symfony

	public function serializeErrors(Form $form)
	{
		$errors = [];
		foreach ($form->getErrors() as $formError) {
			$errors['globals'][] = $formError->getMessage();
		}
		foreach ($form->all() as $childForm) {
			if ($childForm instanceof FormInterface) {
				if ($childErrors = $this->subSerializeErrors($childForm)) {
					$errors['fields'][$childForm->getName()] = $childErrors;
				}
			}
		}

		return $errors;
	}

	private function subSerializeErrors(FormInterface $form)
	{
		$errors = [];
		foreach ($form->getErrors() as $error) {
			$errors[] = $error->getMessage();
		}
		foreach ($form->all() as $childForm) {
			if ($childForm instanceof FormInterface) {
				if ($childErrors = $this->serializeErrors($childForm)) {
					$errors[$childForm->getName()] = $childErrors;
				}
			}
		}

		return $errors;
	}
}