<?php

namespace VankoSoft\Alexandra\ODM\Entity;

use VankoSoft\Alexandra\ODM\Exception\OdmException;

/**
 * @brief	Root class for all ORM Entities.
 * @details	All entities must to be subclasses of this class and all their properties must to be private or protected.
 *			So we have a super type that can be used as an abstract type when we define methods that accept entity as parameter.
 *			By the magic methods 'get' and 'set' defined in this class we receive a base control over the assignment and getting values
 *			of the properties of all ORM entities.
 *			
 */
class Entity
{
	/**
	 * @brief	Magic method 'get'
	 * 
	 * @param	string $property
	 * 
	 * @throws	OrmException
	 * 
	 * @return	mixed
	 */
	public function __get( $property )
	{
		if ( ! property_exists( $this, $property ) )
		{
			throw new OdmException( sprintf( 'Property does not exists: ( %s::%s )', get_class( $this ), $property ) );
		}

		return $this->$property;
	}
	
	/**
	 * @brief	Magic method 'set'
	 * 
	 * @param	string $property
	 * @param	mixed $value
	 * 
	 * @throws	OrmException
	 */
	public function __set( $property, $value )
	{
		if ( ! property_exists( $this, $property ) )
		{
			throw new OdmException( sprintf( 'Property does not exists: ( %s::%s )', get_class( $this ), $property ) );
		}
		
		$this->$property = $value;
	}
}
