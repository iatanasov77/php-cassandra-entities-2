<?php
namespace VankoSoft\Alexandra\DBAL\Adapter;

use VankoSoft\Alexandra\DBAL\Adapter\Driver\DataStax\Adapter as DataStaxAdapter;
use VankoSoft\Alexandra\DBAL\Adapter\Driver\PDO\Adapter as PdoAdapter;
use VankoSoft\Alexandra\DBAL\Adapter\Driver\EvseevNN\Adapter as EvseevAdapter;

/**
 * @brief Driver instance factory
 */
class Driver
{
	const DATASTAX		= 'datastax';
	const PDO			= 'pdo';
	const EVSEEVNN		= 'evseevnn';
	
	/**
	 * @brief	Create a driver instance
	 * 
	 * @param unknown $config
	 * @throws \Exception
	 */
	public static function get( $config )
	{
		switch ( $config['driver'] )
		{
			case Driver::DATASTAX:
				return new DataStaxAdapter( Driver::DATASTAX, $config );
			case Driver::PDO:
				return new PdoAdapter( Driver::PDO, $config );
			case Driver::EVSEEVNN:
				return new EvseevAdapter( Driver::EVSEEVNN, $config );
			default:
				throw new \Exception( 'Unkonwn cassandra driver.' );
		}
	}
}
