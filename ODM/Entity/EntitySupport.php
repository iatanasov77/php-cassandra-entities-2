<?php

namespace VankoSoft\Alexandra\ODM\Entity;

use VankoSoft\Alexandra\DBAL\TableGatewayInterface;
use VankoSoft\Alexandra\ODM\Hydrator\HydratorInterface;

class EntitySupport
{
	/**
	 * @var \VankoSoft\Alexandra\DBAL\TableGatewayInterface	$gw
	 */
	private $gw;
	
	/**
	 * @var \VankoSoft\Alexandra\ODM\Hydrator\HydratorInterface
	 */
	private $hydrator;

	public function __construct( TableGatewayInterface $gw, HydratorInterface $hydrator )
	{
		$this->gw		= $gw;
		$this->hydrator	= $hydrator;
	}
	
	public function gw()
	{
		return $this->gw;
	}
	
	public function hydrator()
	{
		return $this->hydrator;
	}
}
