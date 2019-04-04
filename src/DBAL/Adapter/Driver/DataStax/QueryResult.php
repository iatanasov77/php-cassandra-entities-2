<?php

namespace VankoSoft\Alexandra\DBAL\Adapter\DataStax;

class QueryResult
{
	protected $result;
	
	public function __construct( \Casandra\Rows $result )
	{
		$this->result	= $this->transformResult( $result );
	}
	
	public function result()
	{
		return $this->result;
	}
		
	private function transformResult( \Casandra\Rows $result )
	{
		$arrayResult	= array();
		foreach ( $this->result as $row )
		{
			$arrayResult[]	= $row;
		}
		
		return $arrayResult;
	}
}
