<?php

namespace VankoSoft\Alexandra\ODM\Repository;

use VankoSoft\Alexandra\ODM\Entity\Entity;

interface RepositoryInterface
{
	/**
	 * @brief	Create a new entity object and populate with passed data.
	 * 
	 * @param	array $data
	 * 
	 * @return	VankoSoft\Alexandra\ODM\BaseEntity
	 */
	public function create( array $data );
	
	/**
	 * @brief	Find entitis by provided params and options.
	 * 
	 * @details	Query param is used from QueryPass repositories,
	 *			which use a DbAdapter directly and use an EntityResultSet 
	 *			to fetch entities from result.
	 * 
	 * @param	array $params
	 * @param	array $options
	 * @param	string $query	A CQL query
	 * 
	 * @return	array
	 */
	public function find( array $params, array $options );
	
	/**
	 * @brief	Find just one entity by provided params and options.
	 * 
	 * @details	If db query return more than one entity, the first is returned,
	 *			if db query return empty result, return an instance of EntityNotExists.
	 * 
	 * @param	array $params
	 * @param	array $options
	 * @param	string $query	A CQL query.
	 * 
	 * @return \VankoSoft\Alexandra\ODM\BaseEntity
	 */
	public function findOne( array $params, array $options );
	
	/**
	 * @brief	Remove an entity from database.
	 * 
	 * @param	\VankoSoft\Alexandra\ODM\BaseEntity $entity
	 * @param	string $query	A CQL query
	 * 
	 * @return	void
	 */
	public function remove( Entity $entity );
	
	/**
	 * @brief	Persist an entity into the database.
	 * 
	 * @param	\VankoSoft\Alexandra\ODM\BaseEntity $entity
	 * @param	string $query	A CQL query
	 * 
	 * @return	void
	 */
	public function save( Entity $entity );
}
