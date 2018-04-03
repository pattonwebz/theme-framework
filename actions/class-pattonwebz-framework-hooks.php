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

/**
 * A class filled with static callable methods that act as wrappers for a
 * do_action() call with hooks named as `self::$prefix . '_' . __FUNCTION__`.
 */
class PattonWebz_Framework_Hooks {

	/**
	 * The prefix to use for putting these hooks into a global scope.
	 *
	 * Be sure to override this via constructor when extending otherwise hooks
	 * defined in the theme will be prefixed with the below static string.
	 *
	 * @var string
	 */
	public static $prefix = 'pattonwebz';

	/**
	 * Class constructor for updating static property for prefix.
	 *
	 * @param string $prefix prefix to add when placing things in a global scope.
	 */
	public function __construct( $prefix ) {
		self::$prefix = $prefix;
	}

	/**
	 * Fires before the content wrapper.
	 */
	public static function do_before_content_wrapper() {
		/**
		 * Can be used to output an additional row above the main content row.
		 */
		do_action( self::$prefix . '_' . __FUNCTION__ );
	}

	/**
	 * Fires before the main content container.
	 */
	public static function do_before_main_wrapper() {
		/**
		 * Can be used to output an additional row above the main content row.
		 */
		do_action( self::$prefix . '_' . __FUNCTION__ );
	}

	/**
	 * Usually fires after outputting the_title() on various pages.
	 *
	 * @since 1.2.2
	 *
	 * @param  boolean $echo flag for echo or return.
	 * @param  boolean $type a type setting for the meta we want. Can be blank.
	 */
	public static function do_post_meta( $echo = true, $type = false ) {
		/**
		 * Used to output whatever after post meta items you want
		 */
		do_action( self::$prefix . '_' . __FUNCTION__, $echo, $type );
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
		 * Used to output classnames at the layout select div.
		 */
		do_action( self::$prefix . '_' . __FUNCTION__, $classname_string );
	}

}
