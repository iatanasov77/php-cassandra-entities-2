<?php

namespace VankoSoft\Alexandra\ODM\UnitOfWork;

use VankoSoft\Alexandra\ODM\Entity\Entity;
use VankoSoft\Alexandra\ODM\Entity\EntitySupport;
use VankoSoft\Alexandra\ODM\Hydrator\ArrayHydrator;
use VankoSoft\Alexandra\ODM\Hydrator\HydratorInterface;
use VankoSoft\Alexandra\ODM\Exception\OdmException;

class UnitOfWork implements UnitOfWorkInterface
{
	protected $scheduledForInsert;
	
	protected $scheduledForUpdate;
	
	protected $scheduledForDelete;
	
	protected $entityPersisted;
	
	protected $em;
	
	public function __construct( $em )
	{
		$this->scheduleForInsert	= new \SplObjectStorage;
		$this->scheduleForUpdate	= new \SplObjectStorage;
		$this->scheduleForDelete	= new \SplObjectStorage;
		$this->entityPersisted		= new \SplObjectStorage;
		$this->em                   = $em;
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
	
	public function commit( HydratorInterface $storeHydrator )
	{
		foreach ( $this->scheduleForInsert as $entity )
		{
		    $alias    = get_class( $entity );
		    $params   = $storeHydrator->extract( $entity );
		    $gw       = $this->em->getTableGateway( $alias );
		    $schema   = $this->em->getSchema();
		    if ( ! isset( $schema[$alias] ) )
		    {
		        throw new OdmException( "Missing meta data for this entity" );
		    }
		    //var_dump($schema[$alias]['columns']); die;
		    foreach( $params as $param => $value )
		    {
		        switch ( $schema[$alias]['columns'][$param] )
		        {
		            case 'uuid':
		                $params[$param]   = new \Cassandra\Uuid( $value );
// 		            case 'set':
// 		                $params[$param]   = new \Cassandra\Set(); // Не работи
		        }
		    }
		    var_dump( $params); die;
		    $gw->insert( $schema[$alias], $params );
		    
		}
	}
}
