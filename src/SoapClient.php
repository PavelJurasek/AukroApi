<?php

namespace AukroApi;

use AukroApi\Driver\SoapClientDriver;
use Nette\Utils\Strings;

/**
 * @author Pavel Jurásek
 */
class SoapClient extends \SoapClient
{

	/** @var SoapClientDriver */
	private $clientDriver;

	public function __construct(string $wsdl, SoapClientDriver $clientDriver)
	{
		$wsdl = Strings::endsWith($wsdl, '?wsdl') ? $wsdl : $wsdl . '?wsdl';

		$options = [
			'soap_version' => SOAP_1_1,
			'encoding' => 'UTF-8',
			'trace' => TRUE,
			'exceptions' => TRUE,
			'cache_wsdl' => WSDL_CACHE_DISK,
			'features' => SOAP_SINGLE_ELEMENT_ARRAYS,
		];

		parent::__construct($wsdl, $options);
		$this->clientDriver = $clientDriver;
	}

	/** @see http://aukro.cz/webapi/documentation.php/show/id,1099 */
	public function doLoginEnc(array $parameters)
	{
		return $this->__soapCall(__FUNCTION__, ['WebApi' => $parameters]);
	}

	/**
	 * @param string $request
	 * @param string $location
	 * @param string $action
	 * @param int $version
	 * @param int $oneWay
	 *
	 * @return string|null
	 */
	public function __doRequest($request, $location, $action, $version, $oneWay = 0)
	{
		$response = $this->clientDriver->send($request, $location, $action, $version);
		if ($oneWay === 1) {
			return null;
		}
		return $response;
	}

}
