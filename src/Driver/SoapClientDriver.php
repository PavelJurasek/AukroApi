<?php

namespace AukroApi\Driver;

/**
 * @author slevomat/eet-client
 */
interface SoapClientDriver
{

	public function send(string $request, string $location, string $action, int $soapVersion): string;

}
