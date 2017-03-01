<?php

/**
 * Test: AukroApi\Session\NetteSessionHandler.
 *
 * @testCase Test\AukroApi\Session\NetteSessionHandlerTest
 * @author Pavel Jurásek
 * @package App\AukroApi\Session
 */

namespace Test\AukroApi\Session;

use AukroApi\Session\NetteSessionHandler;
use Nette\Http\Session;
use Tester;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * @author Pavel Jurásek
 */
class NetteSessionHandlerTest extends Tester\TestCase
{

    public function testBasic()
    {
		$originalSession = \Mockery::mock(Session::class);
		$originalSession->shouldReceive('isStarted')
			->andReturn(FALSE);

		$session = new \Kdyby\FakeSession\Session($originalSession);
		$session->disableNative();

		$handler = new NetteSessionHandler($session);

		Assert::null($handler->load());

		$login = new \stdClass;
		$login->sessionHandlePart = 'abc';
		$login->userId = 1;
		$login->serverTime = time();

		$handler->store($login);
		Assert::type(\stdClass::class, $handler->load());

		$handler->clear();
		Assert::null($handler->load());

		$handler->store($login);
		Assert::type(\stdClass::class, $handler->load());

		$login->serverTime -= 3601;
		Assert::null($handler->load());
    }

    public function tearDown()
    {
    	parent::tearDown();

    	\Mockery::close();
    }

}

(new NetteSessionHandlerTest())->run();
