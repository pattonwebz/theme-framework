<?php
/**
 * This file contains a class reposible for adding widget areas (sidebars).
 */

namespace PattonWebz\Framework;

/**
 * Registers widget areas for the theme.
 */
class Widget_Areas {

	use Common\Prefix;

	/**
	 * Conscructor for this class.
	 */
	public function __construct() {
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
			'id'            => $this->prefix . '-main-sidebar',
			'description'   => __( 'Widgets placed in this area will appear on all posts and pages with a sidebar.', 'pattonwebz' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside><hr class="hr-row-divider">',
			'before_title'  => '<h3 class="widget-title h4">',
			'after_title'   => '</h3>',
		) );

	}
}
