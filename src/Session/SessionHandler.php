<?php

namespace AukroApi\Session;

/**
 * @author Pavel Jurásek
 */
interface SessionHandler
{

	/** @return \stdClass|NULL */
	public function load();

	public function store(\stdClass $loginSession);

	public function clear();

}
