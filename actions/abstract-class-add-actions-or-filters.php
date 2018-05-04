<?php
/**
 * Base class to use when adding actions or filters in the theme. This class is
 * abstract - not intended to be instansiated on it's own.
 *
 * @package Pattonwebz_Framework
 * @since 1.0.0
 */

namespace PattonWebz\Framework;

/**
 * A Class that can be extended to add any actions or filters in a theme. Any
 * functions being used as an action or filter can optionally be added to the
 * extended class if it makes sense, but holding externally might make more
 * sense in the long run.
 */
class Add_Actions_Or_Filters implements Add_Actions_Or_Filters_Interface {

	use Common\Prefix;

	/**
	 * At instantiation you are expected to pass a prefix string, no need to
	 * no need to override this method.
	 *
	 * @param string $prefix prefix to add when placing things in a global scope.
	 */
	public function __construct( $prefix = null, $instance = null ) {
		if ( '' !== $prefix ) {
			$this->$prefix = $prefix;
		}
		if ( '' !== $instance ) {

		}
		/**
		 * Override the methods called below to add your own actions or filters.
		 * Also include callable methods for each of them where apropriate.
		 */
		// this is called from outside the class.
		//$this->init();

	}

	/**
	 * This method should lead to calling of add_action() or add_filter() calls.
	 */
	public function init() {
		/**
		 * Place add_action() or add_filter() calls here.
		 */
	}

}
