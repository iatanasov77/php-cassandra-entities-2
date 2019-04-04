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
