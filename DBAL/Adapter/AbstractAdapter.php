<?php

namespace VankoSoft\Alexandra\DBAL\Adapter;

use VankoSoft\Alexandra\DBAL\AdapterInterface;
use VankoSoft\Alexandra\DBAL\Logger\LoggerInterface;
use VankoSoft\Alexandra\DBAL\Logger\LogLevel;

abstract class AbstractAdapter implements AdapterInterface
{
	const DEFAULT_BATCH		= 'default';
	
	/**
	 * @var	mixed $db
	 */
	protected $db;
	
	/**
	 * @brief	Driver ID
	 * 
	 * @var		string $driver
	 */
	protected $driver;
	
	/**
	 * @details	Named batch store
	 *
	 * @var	array $batch;
	 */
	protected $batch;
	
	/**
	 * @var \VankoSoft\Alexandra\DBAL\Logger\LoggerInterface $logger
	 */
	protected $logger;
	
	public function __construct( $driver, array $config )
	{
		$this->driver	= $driver;
		$this->batch	= array();
		
		$this->_init( $config );
	}
	
	public function __destruct()
	{
		$this->close();
	}
	
	public function db()
	{
	    return $this->db;
	}
	
	public function driver()
	{
		return $this->driver;
	}
	
	public function setLogger( LoggerInterface $logger )
	{
		$this->logger	= $logger;
	}
	
	public function query( $cql, array $params = array(), array $options = array() )
	{
		if ( $this->logger )
		{
			$this->_logQuery( $cql, $params );
		}
	
		return $this->_execute( $cql, $params, $options );
	}
	
	//////////////////////////////////////////////////////////////////////////////////////////////
	
	protected abstract function _init( array $config );
	
	protected abstract function _execute( $cql, array $params = array(), array $options = array() );
	
	protected function _logQuery( $cql, array $params )
	{
		//$keys	= array_map( function( $k ){ return ':'  .$k; }, array_keys( $params ) );
		//$logCql	= str_replace( $keys, $params, $cql );
	
		//$this->logger->log( $logCql, LogLevel::INFO );
	}
}
