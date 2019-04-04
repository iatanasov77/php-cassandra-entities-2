<?php

namespace VankoSoft\Alexandra\DBAL;

interface AdapterInterface
{
	function driver();
	
	function close();
	
	function query( $cql, array $params, array $options );
	
	function schema();
	
	function beginBatch( $batchType, $batch );
	
	function applyBatch( $batch );
	
	function queryBatch( $cql, array $params, $batch );
}
