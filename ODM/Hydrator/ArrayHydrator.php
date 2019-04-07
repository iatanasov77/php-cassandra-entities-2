<?php

namespace VankoSoft\Alexandra\ODM\Hydrator;

use VankoSoft\Alexandra\ODM\Hydrator\HydratorInterface;
use VankoSoft\Alexandra\ODM\Entity\Entity;
use VankoSoft\Alexandra\ODM\CamelCaseTrait;

class ArrayHydrator extends Hydrator
{
	use CamelCaseTrait;

	/**
	 *
	 * @param	\VankoSoft\Alexandra\ODM\Entity\Entity $entity
	 *
	 * @return	array
	 */
	public function extract( Entity $entity )
	{
	    $columns   = $this->config[get_class( $entity )]['columns'];
	    
		$row      = array();
		foreach ( $columns as $column => $type )
		{
			$property	= lcfirst( $this->camelize( $column ) );
			$row[$column]	= $entity->$property;
		}

		return $row;
	}

	/**
	 *
	 * @param	\VankoSoft\Alexandra\ODM\Entity\Entity $entity
	 * @param	mixed $rowData
	 *
	 * @return void
	 */
	public function hydrate( Entity &$entity, $rowData )
	{
		foreach ( $rowData as $key => $value )
		{
			$property	= lcfirst( $this->camelize( $key ) );
			$entity->$property	= $value;
		}
	}
}
