<?php

namespace VankoSoft\Alexandra\ODM\Hydrator;

use VankoSoft\Alexandra\ODM\Hydrator\HydratorInterface;
use VankoSoft\Alexandra\ODM\Entity\Entity;
use VankoSoft\Alexandra\ODM\CamelCaseTrait;

class DataStaxHydrator implements HydratorInterface
{
	use CamelCaseTrait;
	
	private $tableMeta;
	
	public function __construct( $tableMeta )
	{
		$this->tableMeta	= $tableMeta;
	}
	
	/**
	 * 
	 * @param	\VankoSoft\Alexandra\ODM\Entity\Entity $entity
	 * 
	 * @return	array
	 */
	public function extract( Entity $entity)
	{
		$row	= array();
		foreach ( $this->tableMeta['columns'] as $column => $meta )
		{
			$property	= lcfirst( $this->camelize( $column ) );
			switch ( $meta['type'] )
			{
				case 'float':
					$row[$column]	= new \Cassanra\Float( $entity->$property );
			
					break;
				case 'set':
					$row[$column]	= new \Cassanra\Set( $entity->$property );
						
					break;
				case 'map':
					$row[$column]	= new \Cassanra\Map( $entity->$property );
			
					break;
				default:
					$row[$column]	= $entity->$property;
			}
		}
		
		return $row;
	}
	
	/**
	 * @brief	Hydrate an entity with passed row columns and return an array list of updated columns
	 * 
	 * @param	\VankoSoft\Alexandra\ODM\Entity\Entity $entity
	 * @param	mixed $rowData
	 * 
	 * @return	array
	 */
	public function hydrate( Entity &$entity, $rowData )
	{
		$updatedColumns	= array();
		foreach ( $rowData as $key => $value )
		{
			$property	= lcfirst( $this->camelize( $key ) );
			switch ( true )
			{
				case ( $value instanceof \Cassandra\Float ):
					if ( $entity->$property	!== $value->value() )
					{
						$entity->$property	= $value->value();
						$updatedColumns[]	= $key;
					}
						
					break;
				case ( $value instanceof \Cassandra\Set ):
					if ( $entity->$property	!== $value->values() )
					{
						$entity->$property	= $value->values();
						$updatedColumns[]	= $key;
					}
					
					break;
				case ( $value instanceof \Cassandra\Map ):
					$arrayMap	= array_map( $value->keys(), $value->values() );
					if ( $entity->$property	!== $arrayMap )
					{
						$entity->$property	= $arrayMap;
						$updatedColumns[]	= $key;
					}
					
					break;
				default:
					if ( $entity->$property	!== $value )
					{
						$entity->$property	= $value;
						$updatedColumns[]	= $key;
					}
			}
		}
		
		return $updatedColumns;
	}
}
