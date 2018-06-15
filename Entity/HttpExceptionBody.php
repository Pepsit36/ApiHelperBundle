<?php

namespace Pepsit36\ApiHelperBundle\Entity;

class HttpExceptionBody
{
	/**
	 * @var string
	 */
	private $message;
	/**
	 * @var mixed[]
	 */
	private $payload;

	public function __construct(string $message, array $payload = null)
	{
		$this->message = $message;
		$this->payload = $payload;
	}

	public function getMessage(): string
	{
		return $this->message;
	}

	public function setMessage(string $message): self
	{
		$this->message = $message;
		return $this;
	}

	public function getPayload(): ?array
	{
		return $this->payload;
	}

	public function setPayload(?array $payload): self
	{
		$this->payload = $payload;
		return $this;
	}

}