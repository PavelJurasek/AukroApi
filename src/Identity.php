<?php

namespace AukroApi;

/**
 * @author Pavel Jurásek
 */
class Identity
{

	/** @var string */
	private $username;

	/** @var string */
	private $password;

	/** @var string */
	private $apiKey;

	public function __construct(string $username, string $password, string $apiKey)
	{
		$this->username = $username;
		$this->password = $password;
		$this->apiKey = $apiKey;
	}

	public function getUsername(): string
	{
		return $this->username;
	}

	public function getPassword(): string
	{
		return $this->password;
	}

	public function getApiKey(): string
	{
		return $this->apiKey;
	}

}
