<?php

namespace VankoSoft\Alexandra\DBAL\TableGateway;

class QueryBuilder
{
	/**
	 * @brief	Build a select query.
	 *
	 * @param	string $tableName
	 * @param	array $columns
	 * @param	array $keys
	 *
	 * @return	string
	 */
	public function select( $tableName, $columns, $keys = null )
	{
	    if ( empty( $columns ) )
	    {
	        $columns[] = ' * ';
	    }
	    $columns   = empty( $columns ) ? ' * ' : $columns;
		$query	   = sprintf( "SELECT %s FROM %s", implode( ', ', $columns ), $tableName );
	
		if ( is_array( $keys ) && ! empty( $keys ) )
		{
			$where	=  implode(
								' AND ',
								array_map(
									function ( $v, $k ) { return sprintf( "%s = :%s", $k, $v ); },
									$keys,
									$keys
								)
							);
	
			$query	.= ' WHERE ' .$where;
		}
			
		return $query;
	}
	
	/**
	 * @brief	Build an insert cql query;
	 *
	 * @param	string $tableName
	 * @param	array $keys
	 *
	 * @return	string
	 */
	public function insert( $tableName, $columns, $params )
	{
	    $values    = '';
	    foreach ( $columns as $k => $v )
	    {
	        $values   .= isset ( $params[$k] ) ? ':' . $k . "," : "'" . $v . "',";
	        
	    }
		$query	= sprintf( 
							'INSERT INTO  %s ( %s ) VALUES ( %s )',
							$tableName,
							implode( ', ', array_keys( $columns ) ),
							rtrim( $values, ',' )
						);
		
		return $query;
	}
	
	/**
	 * @brief	Build an update cql query;
	 *
	 * @param	string $tableName
	 * @param	array $keys
	 * @param	array $primaryKeys
	 *
	 * @return	string
	 */
	public function update( $tableName, $keys, $primaryKeys )
	{
		$setKeys		= array_diff( $keys, $primaryKeys );
	
		$setString		= implode(', ', array_map(
				function ( $v, $k ) { return sprintf( "%s = :%s", $k, $v ); },
				$setKeys,
				$setKeys
				) );
			
		$whereString	= implode(' AND ', array_map(
				function ( $v, $k ) { return sprintf( "%s = :%s", $k, $v ); },
				$primaryKeys,
				$primaryKeys
				) );
	
		$query			= sprintf( 'UPDATE %s SET %s WHERE %s', $tableName, $setString, $whereString );
	
		return $query;
	}
	
	/**
	 * @brief	Build an delete cql query;
	 *
	 * @param	string $tableName
	 * @param	array $keys
	 * @param	array $primaryKeys
	 *
	 * @return	string
	 */
	public function delete( $tableName, $primaryKeys )
	{
		$whereString	= implode(' AND ', array_map(
				function ( $v, $k ) { return sprintf( "%s = :%s", $k, $v ); },
				$primaryKeys,
				$primaryKeys
				) );
	
		$query			= sprintf( 'DELETE FROM %s WHERE %s', $tableName, $whereString );
	
		return $query;
	}
}