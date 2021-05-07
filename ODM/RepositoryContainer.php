<?php

namespace VankoSoft\Alexandra\ODM;

use Noodlehaus\Config as NoodlehausConfig;

use VankoSoft\Alexandra\DBAL\Connection\Connection;
use VankoSoft\Alexandra\ODM\UnitOfWork\UnitOfWork;
use VankoSoft\Alexandra\ODM\Entity\Entity;
use VankoSoft\Alexandra\ODM\Entity\EntitySupport;
use VankoSoft\Alexandra\ODM\Hydrator\HydratorFactory;
use VankoSoft\Alexandra\DBAL\TableGateway\TableGateway;
use VankoSoft\Alexandra\ODM\Hydrator\ArrayHydrator;
use VankoSoft\Alexandra\ODM\Hydrator\Hydrator;
use VankoSoft\Alexandra\ODM\Hydrator\DataStaxHydrator;
use VankoSoft\Alexandra\ODM\Exception\OdmException;
use VankoSoft\Alexandra\DBAL\Adapter\Driver\DataStax\Schema;

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
	
	protected $gw;
	
	/**
	 * @var \VankoSoft\Alexandra\ODM\UnitOfWork\UnitOfWorkInterface $uow;
	 */
	protected $uow;
	
	/**
	 * @var array $repositories
	 */
	protected $repositories;
	
	protected $schema;
	
	/**
	 * @var string $kernelProjectDir
	 */
	protected $kernelProjectDir;
	
	/**
	 * @brief	Repository container constructor.
	 * 
	 * @param	\Enigma\Library\Database\Alexandra\Adapter\Adapter $config
	 * 
	 * @return	void
	 */
	public function __construct( NoodlehausConfig $config, string $kernelProjectDir )
	{
		$this->config             = $config;
		$connection               = new Connection( $config->get( 'connection' ), $config->get( 'logger' ) );
		
		$this->db			      = $connection->get( $config->get( 'preferences.connection' ) );
		$this->uow                = new UnitOfWork( $this );
		$this->repositories	      = array();
		$this->kernelProjectDir   = $kernelProjectDir;
		
		$this->initMetaData();
		$this->schema             = $config->get( 'schema' );
	}
	
	public function getUnitOfWork()
	{
	    return $this->uow;
	}
	
	public function getConfig()
	{
	    return $this->config;
	}
	
	public function getSchema()
	{
	    return $this->schema;
	}
	
	public function getTableGateway( $entityAlias )
	{
	    return $this->gw[$entityAlias];
	}
	
	/**
	 * @copydoc	\VankoSoft\Alexandra\ODM\RepositoryContainerInterface::get()
	 */
	public function get( $alias )
	{
	    //var_dump($this->schema); die;
	    
		// Repositories lazy Loading. If repository is not loaded , try to load it.
		if ( ! isset( $this->repositories[$alias] ) )
		{
			$repository					= $this->config->get( 'repository.' . $alias . '.repository' );
			$entity						= $this->config->get( 'repository.' . $alias . '.entity' );
			$table						= $this->config->get( 'repository.' . $alias . '.table' );
			$columns                    = $this->config->get( 'repository.' . $alias . '.columns' );
			
			//$tableSchema                = $this->schema[$alias];
			$tableSchema                = $this->schema[$table];
			$this->gw[$alias]		    = new TableGateway(
															$table,
															$tableSchema,
															$this->db
														);
			
			$hydrator                   = $this->getHydrator( \VankoSoft\Alexandra\ODM\Hydrator\Hydrator::DATASTAX_HYDRATOR );
// 			$hydrator					= HydratorFactory::get(
// 																$this->db->driver(),
// 																$this->config->get( 'schema.' . $table )
// 															);
			
			$entitySupport				= new EntitySupport( $this->gw[$alias], $hydrator );
			
			$this->repositories[$alias]	= new $repository( $entity, $entitySupport, $this->uow, $columns );
		}
		
		return $this->repositories[$alias];
	}
	
	public function commit()
	{
		$this->uow->commit( $this->getHydrator( Hydrator::ARRAY_HYDRATOR ) );
	}
	
	public function getHydrator( $mode )
	{
	    switch ( $mode )
	    {
	        case Hydrator::DATASTAX_HYDRATOR:
	            return new DataStaxHydrator( $this );
	            
	        case Hydrator::ARRAY_HYDRATOR:
	            return new ArrayHydrator( $this );
	            
	        default:
	            new OdmException( "Invalid Hydrator Mode!" );
	    }
	    
	}
	
	private function initMetaData()
	{
	    // Create Schema meta if not exists
	    $schemaPath = $this->kernelProjectDir . '/var/schema.json';
	    if( ! file_exists( $schemaPath ) )
	    {
	        $schema = \VankoSoft\Alexandra\DBAL\Adapter\Driver\DataStax\Schema::create(
	            $this->config->get( 'repository' ), $this->db->schema()
	        );
	        file_put_contents( $schemaPath, json_encode( $schema ) );
	    }
	}
}
