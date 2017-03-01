<?php

namespace AukroApi\Driver;

/**
 * @author slevomat/eet-client
 */
class DriverRequestFailedException extends \Exception
{

	public function __construct(\Throwable $e)
	{
		parent::__construct($e->getMessage(), $e->getCode(), $e);
	}

}
