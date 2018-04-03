<?php
/**
 * The main class for registering widget areas (sidebars).
 *
 * @package Pattonwebz_Framework
 * @since 1.0.0
 */

/**
 * Registers widget areas.
 */
class PattonWebz_Framework_Widget_Areas {

	/**
	 * The prefix to use when registering things into a global scope.
	 *
	 * @access public
	 * @var string
	 */
	public static $prefix = 'pattonwebz';

	/**
	 * Conscructor for this class.
	 *
	 * @param string $prefix prefix to add when placing things in a global scope.
	 */
	public function __construct( $prefix = '' ) {
		self::$prefix = $prefix;
		$this->hook_registration_at_widgets_init();
	}
	/**
	 * Function hooked to widgets_init to add our sidebars.
	 */
	private function hook_registration_at_widgets_init() {
		add_action( 'widgets_init', array( $this, 'register_theme_widget_areas' ) );
	}

	/**
	 * Register this themes widget areas.
	 */
	public function register_theme_widget_areas() {

		/**
		 * You will likely want to completely override this function when
		 * extending. Below call is just as a reference example.
		 */
		register_sidebar( array(
			'name'          => __( 'Main Sidebar', 'pattonwebz' ),
			'id'            => self::$prefix . '-main-sidebar',
			'description'   => __( 'Widgets placed in this area will appear on all posts and pages with a sidebar.', 'pattonwebz' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside><hr class="hr-row-divider">',
			'before_title'  => '<h3 class="widget-title h4">',
			'after_title'   => '</h3>',
		) );

	}
}
