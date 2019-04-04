<?php

namespace VankoSoft\Alexandra\DBAL\Adapter\Driver\EvseevNN;

use evseevnn\Cassandra\Database;

use VankoSoft\Alexandra\DBAL\Adapter\AbstractAdapter;

class Adapter extends AbstractAdapter
{
	const BATCH_LOGGED		= 1;
	const BATCH_UNLOGGED	= 2;
	const BATCH_COUNTER		= 3;
	
	public function close()
	{
		$this->db->disconnect();
	}
	
	public function schema()
	{
		return $this->db->schema()->keyspace("vs_dev");
	}
	
	public function beginBatch( $batchType = self::BATCH_UNLOGGED, $batch = self::DEFAULT_BATCH )
	{
		$this->batch[$batch]	= clone $this->db;
		
		switch ( $batchType )
		{
			case self::BATCH_LOGGED:
				$this->batch[$batch]->beginBatch();
				break;
			case self::BATCH_UNLOGGED:
				$this->batch[$batch]->beginUnloggedBatch();
				break;
			case self::BATCH_COUNTER:
				$this->batch[$batch]->begnCounterBatch();
				break;
			default:
				unset( $this->batch[$batch] );
				throw new \Exception( 'Unknown batch type' );
		}
	}
	
	public function applyBatch( $batch = self::DEFAULT_BATCH )
	{
		$result	= $this->batch[$batch]->applyBatch();
		unset( $this->batch[$batch] );
		
		return $result;
	}
	
	public function queryBatch( $cql, array $params, $batch = self::DEFAULT_BATCH )
	{
		if ( $this->logger )
		{
			$this->_logQuery( $cql, $params );
		}
		
		return $this->batch[$batch]->query( $cql, $params );
	}
	
	/////////////////////////////////////////////////////////////////////////////////////////////
	
	protected function _init( array $config )
	{
		$this->db	= new Database( $config['contact_points'], $config['keyspace'] );
		$this->db->connect();
	}
	
	protected function _execute()
	{
		return $this->db->query( $cql, $params );
	}
}
