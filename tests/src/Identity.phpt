<?php

/**
 * Test: AukroApi\Identity.
 *
 * @testCase Test\AukroApi\IdentityTest
 * @author Pavel Jurásek
 * @package App\AukroApi
 */

namespace Test\AukroApi;

use AukroApi\Identity;
use Tester;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

/**
 * @author Pavel Jurásek
 */
class IdentityTest extends Tester\TestCase
{

    public function testBasic()
    {
		$identity = new Identity('username', 'password', 'key');

		Assert::same('username', $identity->getUsername());
		Assert::same('password', $identity->getPassword());
		Assert::same('key', $identity->getApiKey());
    }

}

(new IdentityTest())->run();
