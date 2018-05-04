<?php
/**
 * Sets up any action hooks.
 *
 * Hooks are placed into a global scope so use the $prefix property to namespace
 * them correctly and prevent possible collisions.
 *
 * @package PattonWebz Theme Framework
 * @since   1.0.0
 */

namespace PattonWebz\Framework;

/**
 * A class filled with static callable methods that act as wrappers for a
 * do_action() call with hooks named in {prefix}_{function_name} format.
 */
class Hooks {

	use Common\Prefix_Static;

	public static function test_positional_hooks( string $hook = null ) {
		$hooks = array(
			'do_before_content_wrapper',
			'do_after_content_wrapper',
			'do_before_main_wrapper',
			'do_after_main_wrapper',
			'do_before_title',
			'do_after_title',
			'do_before_content',
			'do_after_content',
			'do_before_footer',
			'do_after_footer',
		);
		if ( null !== $hook ) {
			if ( in_array( $hook, $hooks, true ) ) {
				$exists = true;
			} else {
				$exists = false;
			}
		}
		return $exists;
	}

	public static function do_anon_hook( string $hook = null ) {
		if ( null === $hook || ! self::test_positional_hooks( "$hook" ) ) {
			// something is up... return early.
			return;
		}
		error_log( self::$prefix . '_' . $hook, 0 );
		do_action( self::$prefix . '_' . $hook );
	}

	/**
	 * Fires during the main row div before content layout is decided and is
	 * responsible for ouptut of classes and ids to handle overall page layout.
	 *
	 * @since 1.3.0
	 *
	 * @param string $classname_string  a string containing any classnames
	 *                                  to output for layout reasons.
	 */
	public static function do_layout_selection( $classname_string = 'row' ) {
		/**
		 * Used to output classnames at a high level wrapper div. Can be used
		 * as a way to define layouts in the main content section.
		 */
		error_log( self::$prefix . '_' . __FUNCTION__, 0 );
		do_action( self::$prefix . '_' . __FUNCTION__, $classname_string );
	}

}
