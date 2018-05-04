<?php

namespace PattonWebz\Framework\Common;

/**
 * This trait handles setting the a prefix property with a default value and
 * adds setter/getter methods for updating and reading from the containing class.
 */
trait Prefix {

	/**
	 * The prefix to be used when putting anything into global scope.
	 *
	 * Default value is framework prefix - 'pattonwebz'. Use set_prefix() to
	 * update it.
	 *
	 * @access private
	 * @var string
	 */
	private $prefix = 'pattonwebz';

	/**
	 * Getter for the prefix property.
	 *
	 * @access public
	 * @return string
	 */
	public function get_prefix() {
		return (string) $this->prefix;
	}

	/**
	 * Setter for the prefix property.
	 *
	 * @access public
	 * @param  string $prefix The prefix string to be used throughout the class
	 * when placing items into a global scope of any kind.
	 */
	public function set_prefix( string $prefix = null ) {
		if ( null !== $prefix ) {
			$this->prefix = $prefix;
		}
	}
}
