<?php

namespace VankoSoft\Alexandra\ODM;

/**
 * @brief Entity manager interface.
 */
interface RepositoryContainerInterface
{
	/**
	 * @brief	Create repository if not created for a repository alias.
	 * 
	 * @details	This method play role of a factory for all entity repositories.
	 * 
	 * @param	string $alias
	 * 
	 * @throws \VankoSoft\Alexandra\ODM\OrmException
	 * 
	 * @return \VankoSoft\Alexandra\ODM\Repository\EntityRepositoryInterface
	 */
	public function get( $alias );
	
	public function commit();
}
