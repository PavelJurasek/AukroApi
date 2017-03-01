<?php

namespace AukroApi\Session;

use Nette\Http\SessionSection;
use Nette\Http\Session;

/**
 * @author Pavel Jurásek
 */
class NetteSessionHandler implements SessionHandler
{

	/** @var Session */
	private $session;

	public function __construct(Session $session)
	{
		$this->session = $session;
	}

	/**
	 * @return \stdClass|NULL
	 */
	public function load()
	{
		$section = $this->getSection();

		$loginData = $section->offsetGet('loginSession');

		if (!$loginData || $loginData->serverTime+3600 < time()) {
			return NULL;
		}

		return $loginData;
	}

	public function store(\stdClass $loginSession): void
	{
		$section = $this->getSection();

		$section->offsetSet('loginSession', $loginSession);
	}

	public function clear(): void
	{
		$this->getSection()->remove();
	}

	private function getSection(): SessionSection
	{
		return $this->session->getSection('_aukroApi')->setExpiration('+1 hour');
	}

}
