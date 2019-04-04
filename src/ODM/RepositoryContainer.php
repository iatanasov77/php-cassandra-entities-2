<?php

namespace VankoSoft\Alexandra\ODM;

use Noodlehaus\Config as NoodlehausConfig;

use VankoSoft\Alexandra\DBAL\Connection\Connection;
use VankoSoft\Alexandra\ODM\UnitOfWork\UnitOfWork;
use VankoSoft\Alexandra\ODM\Entity\Entity;
use VankoSoft\Alexandra\ODM\Entity\EntitySupport;
use VankoSoft\Alexandra\ODM\Hydrator\HydratorFactory;
use VankoSoft\Alexandra\DBAL\TableGateway\TableGateway;

/**
 * @brief	EntityManager Service.
 * @details	This service play a role of a Repository Factory.
 */
class RepositoryContainer implements RepositoryContainerInterface
{	
	/**
	 * @var \VankoSoft\Alexandra\DBAL\AdapterInterface $db
	 */
	protected $db;
	
	protected $config;
	
	/**
	 * @var \VankoSoft\Alexandra\ODM\UnitOfWork\UnitOfWorkInterface $uow;
	 */
	protected $uow;
	
	/**
	 * @var \VankoSoft\Alexandra\ODM\EntityHydrator $entityHydrator
	 */
	protected $entityHydrator;
	
	/**
	 * @var array $repositories
	 */
	protected $repositories;
	
	/**
	 * @brief	Repository container constructor.
	 * 
	 * @param	\Enigma\Library\Database\Alexandra\Adapter\Adapter $config
	 * 
	 * @return	void
	 */
	public function __construct( NoodlehausConfig $config )
	{
		$this->config		= $config;
		$connection			= new Connection( $config->get( 'connection' ), $config->get( 'logger' ) );
		
		$this->db			= $connection->get( $config->get( 'preferences.connection' ) );
		$this->uow			= new UnitOfWork();
		$this->repositories	= array();
	}
	
	/**
	 * @copydoc	\VankoSoft\Alexandra\ODM\RepositoryContainerInterface::get()
	 */
	public function get( $alias )
	{
		// Repositories lazy Loading. If repository is not loaded , try to load it.
		if ( ! isset( $this->repositories[$alias] ) )
		{
			$entityBase		= '\VankoSoft\Alexandra\ODM\Entity\Entity';
			$repositoryBase	= '\VankoSoft\Alexandra\ODM\Repository\RepositoryInterface';
			
			$repository					= $this->config->get( 'repository.' . $alias . '.repository' );
			$entity						= $this->config->get( 'repository.' . $alias . '.entity' );
			$table						= $this->config->get( 'repository.' . $alias . '.table' );
			
			$tableGateway				= new TableGateway(
															$table,
															$this->config->get( 'schema.' . $table ),
															$this->db
														);
			$hydrator					= HydratorFactory::get(
																$this->db->driver(),
																$this->config->get( 'schema.' . $table )
															);
			$entitySupport				= new EntitySupport( $tableGateway, $hydrator );
			
			$this->repositories[$alias]	= new $repository( $entity, $entitySupport, $this->uow );
		}
		
		return $this->repositories[$alias];
	}
	
	public function commit()
	{
		$this->uow->commit();
	}
}
