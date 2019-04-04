<?php

namespace VankoSoft\Alexandra\ODM\Hydrator;

use VankoSoft\Alexandra\ODM\Entity\Entity;

interface HydratorInterface
{
	public function extract( Entity $entity );
	
	public function hydrate( Entity &$entity, $data );
}