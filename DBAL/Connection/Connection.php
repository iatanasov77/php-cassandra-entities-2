<?php

namespace VankoSoft\Alexandra\DBAL\Connection;

use VankoSoft\Alexandra\DBAL\ConnectionInterface;
use VankoSoft\Alexandra\DBAL\AdapterInterface;
use VankoSoft\Alexandra\DBAL\Adapter\Driver;
use VankoSoft\Alexandra\DBAL\Logger\Logger;

use VankoSoft\Alexandra\DBAL\Exception\ConnectionException;

class Connection implements ConnectionInterface
{
	const DEFAULT_CONNECTION	= 'default';
	
	private $defaultConnection;
	
	private $connections;
	
	private $config;
	
	private $loggerConfig;
	
	public function __construct( array $config, array $loggerConfig )
	{
		$this->config				= $config;
		$this->loggerConfig			= $loggerConfig;
		$this->connections			= array();
		$this->defaultConnection	= self::DEFAULT_CONNECTION;
	}
	
	public function __destruct()
	{
		foreach ( $this->connections as $conn )
		{
			$conn->close();
		}
	}
	
	public function setDefaultConnection( $connectionName )
	{
		$this->defaultConnection	= $connectionName;
	}
	
	public function close( $connectionName )
	{
		if ( $connectionName == $this->defaultConnection )
		{
			throw new ConnectionException( 'Cannot desconnect from default connection!' );
		}
		
		$this->connections[$connectionName]->close();
		unset( $this->connections[$connectionName] );
	}
	
	public function get( $connectionName = null )
	{
		$connectionName	= $connectionName ?: $this->defaultConnection;
		
		if ( ! isset( $this->connections[$connectionName] ) && isset( $this->config[$connectionName] ) )
		{
			$this->connections[$connectionName]	= $this->_connect( $this->config[$connectionName], $this->loggerConfig );
		}
		
		if ( ! isset( $this->connections[$connectionName] ) )
		{
			throw new ConnectionException( 'Cannot connect to database!' );
		}
		
		return $this->connections[$connectionName];
	}
	
	/////////////////////////////////////////////////////////////////////////////////////////////////
	
	/**
	 * @brief	Create a driver connection interface and set connection into connections container
	 * 
	 * @param 	array $config
	 * 
	 * @throws 	\Exception
	 * 
	 * @return 	AdapterInterface
	 */
	protected function _connect( array $config, array $logChanels = array() )
	{
		$adapter	= Driver::get( $config );
		
		if ( ! $adapter instanceof AdapterInterface )
		{
			throw new \Exception( 'Adapter should be an instance of "AdapterInterface".' );
		}
		
		// Set logger if it has defined log chanels
		if	( ! empty( $logChanels ) )
		{
			$adapter->setLogger( new Logger( $logChanels ) );
		}
		
		return $adapter;
	}
}
