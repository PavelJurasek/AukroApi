<?php

namespace AukroApi;

/**
 * @author Pavel Jurásek
 */
class LoginFailedException extends \RuntimeException
{

	public function __construct(\Throwable $e)
	{
		parent::__construct($e->getMessage(), $e->getCode(), $e);
	}

}
