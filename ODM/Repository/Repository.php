<?php

namespace VankoSoft\Alexandra\ODM\Repository;

use VankoSoft\Alexandra\ODM\Entity\Entity;
use VankoSoft\Alexandra\ODM\Entity\EntitySupport;
use VankoSoft\Alexandra\ODM\UnitOfWork\UnitOfWorkInterface;
use VankoSoft\Alexandra\ODM\UnitOfWork\EntityState;
/**
 * @brief	Base class for repositories that used EntityGateway to load and persist entities.
 */
class Repository implements RepositoryInterface
{
	/**
	 * @var string $entityType
	 */
	protected $entityType;
	
	/**
	 * @var \VankoSoft\Alexandra\ODM\Entity\EntitySupport $es
	 */
	protected $es;
	
	/**
	 * @var \VankoSoft\Alexandra\ODM\UnitOfWork\UnitOfWorkInterface;
	 */
	protected $uow;
	
	protected $columns;
	
	/**
	 * @brief	Initialize Entity Repository
	 * 
	 * @param	string $entityType
	 * @param	\VankoSoft\Alexandra\ODM\Entity\EntitySupport $es
	 * 
	 * @return	void
	 */
	public function __construct( $entityType, EntitySupport $es, UnitOfWorkInterface $uow, $columns )
	{
		$this->entityType	= $entityType;
		$this->es			= $es;
		$this->uow			= $uow;
		$this->columns        = $columns;
	}
	
	/**
	 * @copydoc	\VankoSoft\Alexandra\ODM\EntityRepositoryInterface::create()
	 */
	public function create( array $data = array() )
	{		
		$entity	= $this->es->hydrator()->hydrate( new $this->entityType , $data );
		
		$this->uow->schedule( $entity, $this->es, EntityState::NOT_PERSISTED );
		
		return $entity;
	}
	
	/**
	 * @copydoc	\VankoSoft\Alexandra\ODM\EntityRepositoryInterface::find()
	 */
	public function find( array $params = array(), array $options = array() )
	{
		$rows		= $this->es->gw()->select( $params );
		
		$entities	= array();
		foreach ( $rows as $row )
		{
			$entity	= new $this->entityType( $this->columns );
			
			$this->es->hydrator()->hydrate( $entity , $row );
			$this->uow->schedule( $entity, $this->es, EntityState::PERSISTED );
			$entities[]	= $entity;
		}
		
		return $entities;
	}
	
	/**
	 * @copydoc	\VankoSoft\Alexandra\ODM\EntityRepositoryInterface::findOne()
	 */
	public function findOne( array $params, array $options = array() )
	{
		$entities	= $this->find( $params, $options );
		
		return empty( $entities ) ? new EntityNotExists() : $entities[0];
	}
	
	/**
	 * @copydoc	\VankoSoft\Alexandra\ODM\EntityRepositoryInterface::save()
	 */
	public function save( Entity $entity )
	{
		$this->uow->schedule( $entity, $this->es, EntityState::NOT_PERSISTED );
		
	}
	
	/**
	 * @copydoc	\VankoSoft\Alexandra\ODM\EntityRepositoryInterface::remove()
	 */
	public function remove( Entity $entityl )
	{
		$this->uow->schedule( $entity, $this->es, EntityState::REMOVED );
	}
	
	public function increment( $column )
	{
		$this->uow->schedule( $entity, $this->es, EntityState::UPDATED );
	}
	
	public function decrement( $column )
	{
		$this->uow->schedule( $entity, $this->es, EntityState::UPDATED );
	}
}	
