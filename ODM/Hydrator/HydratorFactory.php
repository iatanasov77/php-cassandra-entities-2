<?php

namespace VankoSoft\Alexandra\ODM\Hydrator;

use VankoSoft\Alexandra\DBAL\Adapter\Driver;

class HydratorFactory
{
	public static function get( $connectionType, $tableMeta )
	{
		switch ( $connectionType )
		{
			case Driver::DATASTAX:
				return new DataStaxHydrator( $tableMeta );
			case Driver::EVSEEVNN:
				return new ArrayHydrator( $tableMeta );
			case Driver::PDO:
			default:
				throw new \Exception( 'Not configured hydrator about this driver.' );
		}
	}
}
