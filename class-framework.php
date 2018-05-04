<?php
/**
 * Main Framework Setup Class.
 *
 * @version 1.0.0-alpha
 *
 * @package Pattonwebz_Framework
 * @since 1.0.0
 */

namespace PattonWebz;

/**
 * Class that handles the base of a themes setup and initializations.
 */
class Framework {

	/**
	 * Holds the class version number.
	 *
	 * @var string
	 */
	public static $version = '1.0.0-alpha';

	/**
	 * Prefix to add to anything placed into global namespace.
	 *
	 * @var string
	 */
	public static $prefix = 'pattonwebz';

	/**
	 * Holds the theme version string.
	 *
	 * @var string
	 */
	public $theme_version = '';

	/**
	 * Holds the directory of the theme.
	 *
	 * @var string
	 */
	public $theme_dir = '';

	/**
	 * Holds the uri of the theme.
	 *
	 * @var string
	 */
	public $theme_uri = '';

	/**
	 * The path to the framework package - relative to theme path and should
	 * have no leading slash.
	 *
	 * @var string
	 */
	public $framework_relative_dir = 'inc/framework/';

	/**
	 * Holds an instance of this class.
	 *
	 * @var object
	 */
	public static $instance = null;

	/**
	 * Used to store reference to the themes instantiated sidebars class.
	 *
	 * @var object
	 */
	public $widget_areas = null;

	/**
	 * Used to store reference to the themes instantiated customizer class.
	 *
	 * @var object
	 */
	public $customizer = null;

	/**
	 * Holds the instance used to call the action hooks.
	 *
	 * @var object
	 */
	public $hooks = null;

	/**
	 * Holds the instance used to apply the action adds.
	 *
	 * @var object
	 */
	public $actions = null;

	/**
	 * Holds the instance used to apply the filter adds.
	 *
	 * @var object
	 */
	public $filters = null;

	/**
	 * Holds the instance responsible for adding admin pages in the dashboard.
	 *
	 * @var object
	 */
	public $admin_page = null;

	/**
	 * Constructor function for the class.
	 *
	 * NOTE: When extending you are expected to update the static property
	 * $prefix in the constructor like so: $self::$prefix = 'prefix';
	 */
	private function __construct() {
		// set the static property to the themes prefix - this is used in many
		// places. Anywhere that something is placed into a global scope.
		self::$prefix = 'pattonwebz';

		// Setup some properties for the theme.
		$this->theme_dir     = trailingslashit( get_template_directory() );
		$this->theme_uri     = trailingslashit( get_template_directory_uri() );
		$this->theme_version = wp_get_theme()->get( 'Version' );

		// NOT a magic auto loader :(.
		$this->class_loader();

		// Add some helpers.
		$this->add_helpers();
	}

	/**
	 * Not a magic autoloader :(.
	 */
	protected function class_loader() {
		include_once $this->theme_dir . $this->framework_relative_dir . 'common/trait-prefix.php';
		include_once $this->theme_dir . $this->framework_relative_dir . 'common/trait-prefix-static.php';
		include_once $this->theme_dir . $this->framework_relative_dir . 'common/trait-filter-queue.php';
		// Class to register sidebars.
		include_once $this->theme_dir . $this->framework_relative_dir . 'widget-areas/class-widget-areas.php';
		// these classes contains the static methods that act as hooks.
		include_once $this->theme_dir . $this->framework_relative_dir . 'actions/class-hooks.php';
		// Include the add_action and add_filter calls.
		include_once $this->theme_dir . $this->framework_relative_dir . 'actions/interface-add-actions-or-filters.php';
		include_once $this->theme_dir . $this->framework_relative_dir . 'actions/abstract-class-add-actions-or-filters.php';
		include_once $this->theme_dir . $this->framework_relative_dir . 'actions/class-actions.php';
		include_once $this->theme_dir . $this->framework_relative_dir . 'actions/class-filters.php';
		// These are used for theme admin page.
		include_once $this->theme_dir . $this->framework_relative_dir . 'admin/interface-theme-admin-page.php';
		include_once $this->theme_dir . $this->framework_relative_dir . 'admin/class-admin-page.php';
		// Base class to work with wp_customizer object.
		include_once $this->theme_dir . $this->framework_relative_dir . 'customizer/interface-customizer-holder.php';
		include_once $this->theme_dir . $this->framework_relative_dir . 'customizer/class-customizer.php';

	}

	/**
	 * Add some useful helper functions for the theme.
	 */
	protected function add_helpers() {
		// maybe add some custom functions or sanitization filters here.
		include_once $this->theme_dir . $this->framework_relative_dir . 'extras/class-pattonwebz-framework-sanitizers.php';
	}

	/**
	 * Runs the full framework theme setup functions.
	 *
	 * These functions setup some base items for a full theme. When extending
	 * the framework you are likely to want to provide you own method that
	 * handles setup.
	 */
	private function do_framework_full() {
		// Do theme setup actions.
		$this->do_theme_setup();
		// setup hooks, actions, filters.
		$this->do_framework_partial_hooks();
		// setup the customizer options.
		$this->do_framework_partial_customizer();
		// Admin page.
		$this->add_theme_admin_page();
	}

	/**
	 * Setup framework hooks.
	 *
	 * These hooks will be prefixed with the framework prefix by default.
	 */
	private function do_framework_partial_hooks() {
		$this->add_hooks();
		$this->add_actions_and_filters();
	}

	/**
	 * Setup framework theme customizer options.
	 *
	 * The framework itself does not add any options beyond the help section.
	 */
	private function do_framework_partial_customizer() {
		$this->add_customizer_options();
	}

	/**
	 * Handle any theme setup actions.
	 */
	public function do_theme_setup() {

		// Register theme supported features and set content width.
		add_action( 'after_theme_setup', array( $this, 'theme_support' ) );
		add_action( 'after_setup_theme', array( $this, 'content_width' ), 0 );
		// Add styles and scripts.
		add_action( 'wp_enqueue_scripts', array( $this, 'load_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'load_scripts' ) );

		$this->register_nav_menus();
		$this->register_widget_areas();
	}

	/**
	 * Adds a walker for use with nav manus and regusters nav menu locations.
	 *
	 * NOTE: Intended to be override.
	 */
	public function register_nav_menus() {
		register_nav_menus(
			array(
				self::$prefix . 'primary'   => __( 'Primary Navigation', 'pattonwebz' ),
				self::$prefix . 'secondary' => __( 'Secondary Navigation', 'pattonwez' ),
			)
		);
	}

	/**
	 * Adds widget areas for the theme.
	 *
	 * NOTE: Can be overriden and call parent::function to include the dependancy class.
	 */
	public function register_widget_areas() {

		// create a new instance of the class and store it in the sidebars_instance property.
		$this->widget_areas = new Framework\Widget_Areas();
	}

	/**
	 * Instatiate customizer class for theme.
	 *
	 * NOTE: Can be overriden and call parent::function to include the dependancy class.
	 */
	public function add_customizer_options() {

		// Store the customizer object in this property and pass the 3 paramiters.
		$this->customizer = new Customizer( $this->theme_dir . $this->framework_relative_dir . 'customizer/', $this->theme_uri . $this->framework_relative_dir . 'customizer/', self::setting_defaults() );
	}

	/**
	 * Registers the features this theme supports that need declared.
	 *
	 * NOTE: Can be overriden and call parent::function to setup these basic supports.
	 */
	public function theme_support() {
		// Featured Image support.
		add_theme_support( 'post-thumbnails' );
		// Enables post and comment RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );
		// WP 4.1+ title-tag support.
		add_theme_support( 'title-tag' );
		// We'll want to use these over built in breadcrumbs if available.
		add_theme_support( 'yoast-seo-breadcrumbs' );

	}
	/**
	 * Sets the content width
	 *
	 * NOTE: Can be overriden or filtered with a new value.
	 */
	public function content_width() {
		// set the themes content_width value - is filterable.
		$GLOBALS['content_width'] = apply_filters( self::$prefix . '_content_width', 763 );
	}

	/**
	 * Adds themes styles.
	 *
	 * NOTE: Intended to be overriden. Shown as example.
	 */
	public function load_styles() {
		// Main theme stylesheet.
		wp_enqueue_style( self::$prefix, $this->theme_uri . 'style.css', array(), self::$version );
	}

	/**
	 * Adds theme scripts.
	 *
	 * NOTE: Intended to be overriden. Shown as example.
	 */
	public function load_scripts() {
		wp_enqueue_script( self::$prefix, $this->theme_uri . 'js/scripts.js', array(), self::$version, true );

		// only enqueue comment-reply script on single pages.
		if ( is_single() ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}

	/**
	 * Adds theme hooks.
	 */
	public function add_hooks() {
		/**
		 * Instantiate this class and pass it a prefix to use to update the
		 * static prefix value inside the class.
		 */
		$this->hooks = new Framework\Hooks();
	}

	/**
	 * Adds hooks actions and filters used by the theme in template files.
	 *
	 * NOTE: Can be overriden and call parent::function to include the dependancy class.
	 */
	public function add_actions_and_filters() {
		/**
		 * Create 2 instances of this base class for adding actions and filters.
		 *
		 *  NOTE: When overriding these will likely be different classes that
		 *  both extend the same parent class.
		 */
		$this->actions = new Framework\Actions( self::$prefix );
		$this->filters = new Framework\Filters( self::$prefix );
		$this->actions->init();
		$this->filters->init();
	}

	/**
	 * Adds a theme info/admin page for display in the dashboard.
	 *
	 * @method add_theme_admin_page
	 */
	public function add_theme_admin_page() {
		$this->admin_page = new \PattonWebz\Framework\Admin\Admin_Page();
		$this->admin_page->hook_pages();
	}

	/**
	 * A helper function to return default settings for the customizer items.
	 *
	 * NOTE: Intended to be overriden. You should remember to include the logic
	 * to return single field values.
	 *
	 * @param string $field A string containing an individual field name to get.
	 *
	 * @return array an array of settings in key => value format.
	 */
	public static function setting_defaults( $field = '' ) {

		/**
		 * This is an example array of settings.
		 *
		 * NOTE: You should provide an array of settings like this when overriding.
		 *
		 * @var array
		 */
		$defaults = array(
			'display_brand_text' => 0,
			'layout_selection'   => '',
		);
		// filter the defaults so they can be edited by child theme or plugin.
		$defaults = apply_filters( self::$prefix . '_filter_setting_defaults', $defaults );

		/**
		 * Retain this logic even when overriding.
		 */
		// if we got a specific field request...
		if ( '' !== $field ) {
			if ( array_key_exists( $field, $defaults ) ) {
				// requested field exists, return it's value.
				return $defaults[ $field ];
			}
		}
		// in all other cases we'll return the full array.
		return $defaults;

	}

}
