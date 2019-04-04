<?php

namespace VankoSoft\Alexandra\DBAL;

interface ConnectionInterface
{	
	public function setDefaultConnection( $connectionName );
	
	public function get( $connectionName );
	
	public function close( $connectionName );
}
