<?php

namespace VankoSoft\Alexandra\DBAL\Logger;

use Monolog\Logger as Monolog;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;

class Logger implements LoggerInterface
{
	protected $chanels;

	public function __construct( array $configChanels )
	{
		foreach ( $configChanels as $chanel => $config )
		{
			$this->chanels[$chanel]	= new Monolog( 'Alexandra' . $chanel );
			
			foreach ( $config['handlers'] as $handler => $handlerConfig )
			{	
				$this->chanels[$chanel]->pushHandler( $this->createHandler( $handler, $handlerConfig ) );
			}
		}
	}
	
	public function log( $message, $level, array $data = array() )
	{
		foreach ( $this->chanels as $monolog )
		{
			$this->_log( $monolog, $message, $level, $data );
		}
	}
	
	////////////////////////////////////////////////////////////////////////////////////////
	
	protected function _log( $monolog, $message, $level, array $data = array() )
	{
		switch ( $level )
		{
			case LogLevel::INFO:
				$monolog->info( $message );
				break;
			case LogLevel::NOTICE:
				$monolog->notice( $message );
				break;
			case LogLevel::WARNING:
				$monolog->warning( $message );
				break;
			case LogLevel::ERROR:
				$monolog->error( $message );
				break;
			default:
				throw new \Exception( 'Unknown log level.' );
		}
	}
	
	protected function createHandler( $handler, $handlerConfig )
	{
		switch ( $handler )
		{
			case Handler::FILE:
				return new StreamHandler( $handlerConfig['file'], LogLevel::DEBUG );
				
			case Handler::FIRE_PHP:
				return new FirePHPHandler();
				
			default:
				throw new \Exception( 'Unknown log handler.' );
		}
	}
}
