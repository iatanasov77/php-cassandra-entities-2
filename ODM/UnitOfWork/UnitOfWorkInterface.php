<?php

namespace VankoSoft\Alexandra\ODM\UnitOfWork;

use VankoSoft\Alexandra\ODM\Entity\Entity;
use VankoSoft\Alexandra\ODM\Entity\EntitySupport;

interface UnitOfWorkInterface
{
	function schedule( Entity $entity, EntitySupport $es, $state );
	
	function commit();
}
