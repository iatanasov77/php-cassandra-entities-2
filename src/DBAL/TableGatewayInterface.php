<?php

namespace VankoSoft\Alexandra\DBAL;

interface TableGatewayInterface
{
	public function select( array $columnSet, array $whereMap );
	
	public function insert( array $valueMap );
	
	public function update( array $valueMap, array $whereMap );
	
	public function delete( array $whereMap );
}