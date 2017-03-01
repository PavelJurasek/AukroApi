<?php

namespace AukroApi;

use AukroApi\Driver\DriverRequestFailedException;
use AukroApi\Session\SessionHandler;

/**
 * @method mixed getMyIncomingPayments()
 *
 * @author Pavel Jurásek
 */
class Client
{

	/** @var Identity */
	private $identity;

	/** @var CountryCode */
	private $countryCode;

	/** @var string */
	private $versionKey;

	/** @var array */
	private $requestData;

	/** @var SessionHandler */
	private $sessionHandler;

	/** @var SoapClient */
	private $soapClient;

	public function __construct(Identity $identity, CountryCode $countryCode, string $versionKey, SessionHandler $sessionHandler, SoapClient $soapClient)
	{
		$this->identity = $identity;
		$this->countryCode = $countryCode;
		$this->versionKey = $versionKey;
		$this->sessionHandler = $sessionHandler;
		$this->soapClient = $soapClient;

		$this->requestData = [
			'countryId' => $countryCode->getValue(), //for old function - example: doGetShipmentData
			'countryCode' => $countryCode->getValue(), //for new function
			'webapiKey' => $identity->getApiKey(),
			'localVersion' => $versionKey,
		];
	}

	public function isLogged(): bool
	{
		return $this->sessionHandler->load() !== NULL;
	}

	public function login(): void
	{
		if ($this->isLogged()) {
			return;
		}

		$requestData = $this->combineRequestData([
			'userLogin' => $this->identity->getUsername(),
			'userHashPassword' => $this->identity->getPassword(),
		]);

		try {
			$this->sessionHandler->store($this->soapClient->doLoginEnc($requestData));

		} catch (DriverRequestFailedException $e) {
			throw new LoginFailedException($e);
		} catch (\SoapFault $e) {
			throw new LoginFailedException($e);
		}
	}

	public function logout(): void
	{
		$this->sessionHandler->clear();
	}

	private function combineRequestData(array $data): array
	{
		$requestData = $this->requestData;

		if ($this->isLogged()) {
			$loginData = $this->sessionHandler->load();

			$requestData['sessionId'] = $loginData->sessionHandlePart;
			$requestData['sessionHandle'] = $loginData->sessionHandlePart;
		}

		return array_merge($requestData, $data);
	}

	public function __call($name, array $arguments): \stdClass
	{
		$params = isset($arguments[0]) ? (array) $arguments[0] : [];

		$request = $this->combineRequestData($params);

		$fname = 'do' . ucfirst($name);

		return $this->soapClient->$fname($request);
	}

}
