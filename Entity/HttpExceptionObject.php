<?php

namespace Pepsit36\ApiHelperBundle\Entity;

class HttpExceptionObject
{
	/**
	 * @var int
	 */
	private $code;
	/**
	 * @var HttpExceptionBody
	 */
	private $body;

	/**
	 * @var string
	 */
	private $translationDomain;

	public function __construct(int $code, string $message, array $payload = null, string $translationDomain = null)
	{
		$this->code = $code;
		$this->translationDomain = $translationDomain;
		$this->body = new HttpExceptionBody($message, $payload);
	}

	public function getCode(): int
	{
		return $this->code;
	}

	public function setCode(int $code): self
	{
		$this->code = $code;
		return $this;
	}

	public function getBody(): HttpExceptionBody
	{
		return $this->body;
	}

	public function setBody(HttpExceptionBody $body): self
	{
		$this->body = $body;
		return $this;
	}

	public function getTranslationDomain(): ?string
	{
		return $this->translationDomain;
	}

	public function setTranslationDomain(?string $translationDomain): self
	{
		$this->translationDomain = $translationDomain;
		return $this;
	}

}