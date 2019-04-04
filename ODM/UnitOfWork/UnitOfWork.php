<?php

namespace VankoSoft\Alexandra\ODM\UnitOfWork;

use VankoSoft\Alexandra\ODM\Entity\Entity;
use VankoSoft\Alexandra\ODM\Entity\EntitySupport;

class UnitOfWork implements UnitOfWorkInterface
{
	protected $scheduledForInsert;
	
	protected $scheduledForUpdate;
	
	protected $scheduledForDelete;
	
	protected $entityPersisted;
	
	public function __construct()
	{
		$this->scheduleForInsert	= new \SplObjectStorage;
		$this->scheduleForUpdate	= new \SplObjectStorage;
		$this->scheduleForDelete	= new \SplObjectStorage;
		$this->entityPersisted		= new \SplObjectStorage;
	}
	
	
	public function schedule( Entity $entity, EntitySupport $es, $state )
	{
		switch ( $state )
		{
			case EntityState::PERSISTED:
				$this->entityPersisted->attach( $entity, $es );
				
				break;
			case EntityState::NOT_PERSISTED:
				$this->scheduleForInsert->attach( $entity, $es );
				
				break;
			case EntityState::UPDATED:
				$this->scheduleForUpdate->attach( $entity, $es );
				
				break;
			case EntityState::REMOVED:
				$this->scheduleForDelete->attach( $entity, $es );
				
				break;
			default:
				throw new \Exception( 'Unknown entity state.' );
		}
	}
	
	public function commit()
	{
		
	}
}
