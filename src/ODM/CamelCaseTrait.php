<?php

namespace VankoSoft\Alexandra\ODM;

/**
 * @brief	CamelCaseTrait provide static methods to camelize and decamelize a string.
 */
trait CamelCaseTrait
{
	/**
	 * @brief	Decamelize a string
	 * 
	 * @param	string $string
	 * @param	string $delim
	 * 
	 * @return	string
	 */
	public static function decamelize( $string, $delim = '_' )
	{
		return strtolower(
			preg_replace( 
				'/(?!^)[[:upper:]][[:lower:]]/',
				'$0',
				preg_replace('/(?!^)[[:upper:]]+/', $delim . '$0', $string )
			)
		);
	}

	/**
	 * @brief	Camelize a string.
	 * 
	 * @param	string $string
	 * @param	string $delim
	 * 
	 * @return	string
	 */
	public static function camelize( $string, $delim = '_' )
	{
		return implode( '', array_map( 'ucfirst', explode( $delim, $string ) ) );
	}
}
