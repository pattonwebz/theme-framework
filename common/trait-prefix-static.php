<?php

namespace PattonWebz\Framework\Common;

/**
 * This trait handles setting the a prefix property with a default value and
 * adds setter/getter methods for updating and reading from the containing class.
 */
trait Prefix_Static {

	/**
	 * The prefix to be used when putting anything into global scope.
	 *
	 * Default value is framework prefix - 'pattonwebz'. Use set_prefix() to
	 * update it.
	 *
	 * @access private
	 * @var string
	 */
	private static $prefix = 'pattonwebz';

	/**
	 * Getter for the prefix property.
	 *
	 * @access public
	 * @return string
	 */
	public static function get_prefix() {
		return (string) self::$prefix;
	}

	/**
	 * Setter for the prefix property.
	 *
	 * @access public
	 * @param  string $prefix The prefix string to be used throughout the class
	 * when placing items into a global scope of any kind.
	 */
	public static function set_prefix( string $prefix = null ) {
		if ( null !== $prefix ) {
			self::$prefix = $prefix;
		}
	}
}
