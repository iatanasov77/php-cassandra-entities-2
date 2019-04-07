<?php

namespace VankoSoft\Alexandra\ODM\UnitOfWork;

use VankoSoft\Alexandra\ODM\Entity\Entity;
use VankoSoft\Alexandra\ODM\Entity\EntitySupport;
use VankoSoft\Alexandra\ODM\Hydrator\HydratorInterface;

interface UnitOfWorkInterface
{
	function schedule( Entity $entity, EntitySupport $es, $state );
	
	function commit( HydratorInterface $storeHydrator );
}
