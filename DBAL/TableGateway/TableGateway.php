<?php

namespace VankoSoft\Alexandra\DBAL\TableGateway;

use VankoSoft\Alexandra\DBAL\TableGatewayInterface;
use VankoSoft\Alexandra\DBAL\AdapterInterface;

class TableGateway implements TableGatewayInterface
{
	protected $tableName;
	
	protected $tableMeta;
	
	protected $dbAdapter;
	
	protected $queryBuilder;
	
	public function __construct( $tableName, array $tableMeta, AdapterInterface $dbAdapter )
	{
		$this->tableName	= $tableName;
		
		$this->tableMeta	= $tableMeta;
		
		$this->dbAdapter	= $dbAdapter;
		
		$this->queryBuilder	= new QueryBuilder;
	}
	
	public function select( array $whereMap = array(), array $columnSet = null )
	{
		$columns	= $columnSet ?: array_keys( $this->tableMeta['columns'] );
		$where		= array_keys( $whereMap );
		
		if ( ! empty( $whereMap ) && ! empty( array_diff( $this->tableMeta['partition_keys'], $where ) ) )
		{
			throw new \Exception( 'Primary key params are mandatory when you make query with where clouse.' );
		}
		
		$cql	= $this->queryBuilder->select( $this->tableName, $columns, $where );
		
		return  $this->dbAdapter->query( $cql, $whereMap );
	}
	
	public function insert( array $valueMap )
	{
		$columns	= array_keys( $valueMap );
		
		if ( ! empty( array_diff( $this->tableMeta['columns'], $columns ) ) )
		{
			throw new \Exception( 'Missing columns into passed values.' );
		}
		
		$cql	= $this->queryBuilder->insert( $this->tableName, $columns );
		
		return  $this->dbAdapter->query( $cql, $valueMap );
	}
	
	public function update( array $valueMap, array $whereMap )
	{
		
	}
	
	public function delete( array $whereMap )
	{
		
	}
}
