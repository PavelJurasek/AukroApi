<?php

/**
 * Test: AukroApi\Client.
 *
 * @testCase Test\AukroApi\ClientTest
 * @author Pavel Jurásek
 * @package App\AukroApi
 */

namespace Test\AukroApi;

use AukroApi\Client;
use AukroApi\CountryCode;
use AukroApi\Driver\GuzzleSoapClientDriver;
use AukroApi\Identity;
use AukroApi\Session\SessionHandler;
use AukroApi\SoapClient;
use Tester;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';
$credentials = require_once __DIR__ . '/../credentials.php';

/**
 * @author Pavel Jurásek
 */
class ClientTest extends Tester\TestCase
{

	/** @var array */
	private $credentials;

	public function __construct(array $credentials)
	{
		$this->credentials = $credentials;
	}

    public function testUnit()
    {
		$identity = new Identity('username', 'password', 'key');

		$soap = \Mockery::mock(SoapClient::class);

		$sessionHandler = \Mockery::mock(SessionHandler::class);
		$sessionHandler->shouldReceive('load')
			->andReturn(NULL);

		$client = new Client($identity, CountryCode::get(CountryCode::CZ), 123, $sessionHandler, $soap);

		Assert::false($client->isLogged());
    }

	/***/
	public function testIntegration()
	{
		$identity = new Identity($this->credentials['username'], $this->credentials['password'], $this->credentials['key']);

		$client = new Client($identity, $this->credentials['countryCode'], $this->credentials['versionKey'], $this->getSessionHandler(), $this->getSoapClient());

		Assert::false($client->isLogged());

		$client->login();
		Assert::true($client->isLogged());

		$response = $client->getMyIncomingPayments();
		Assert::type(\stdClass::class, $response);
		Assert::type(\stdClass::class, $response->payTransIncome);

		$client->logout();
		Assert::false($client->isLogged());
	}

    public function tearDown()
    {
    	parent::tearDown();

    	\Mockery::close();
    }

	private function getSessionHandler(): SessionHandler
	{
		$req = (new \Nette\Http\RequestFactory())->createHttpRequest();
		$res = new \Nette\Http\Response();

		return new \AukroApi\Session\NetteSessionHandler(new \Nette\Http\Session($req, $res));
	}

	private function getSoapClient(): SoapClient
	{
		$guzzle = new GuzzleSoapClientDriver(new \GuzzleHttp\Client());

		return new SoapClient($this->credentials['url'], $guzzle);
	}

}

(new ClientTest($credentials))->run();
