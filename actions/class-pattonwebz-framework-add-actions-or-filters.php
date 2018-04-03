<?php
/**
 * Base class to use when adding actions or filters in the theme.
 *
 * @package Pattonwebz_Framework
 * @since 1.0.0
 */

/**
 * A Class that can be extended to add ay actions or filters in a theme. Any
 * functions being used as an action or filter can also be added to the extended
 * class if it makes sense to hold them in a single place.
 */
class PattonWebz_Framework_Add_Actions_Or_Filters {

	/**
	 * Static property to hold prefix used when placing anything into a global
	 * scope.
	 *
	 * @var string
	 */
	public static $prefix = 'pattonwebz';

	/**
	 * At instantiation you are expected to pass a prefix string, no need to
	 * no need to override this method.
	 *
	 * @param string $prefix prefix to add when placing things in a global scope.
	 */
	public function __construct( $prefix = '' ) {
		self::$prefix = $prefix;
		/**
		 * Override the methods called below to add your own actions or filters.
		 * Also include callable methods for each of them where apropriate.
		 */
		$this->setup_actions();
		$this->setup_filters();

	}

	/**
	 * Add actions.
	 */
	public function setup_actions() {

	}

	/**
	 * Add Filters.
	 */
	public function setup_filters() {

	}

}
