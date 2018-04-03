<?php
/**
 * Main Framework Setup Class.
 *
 * @version 1.0.0-alpha
 *
 * @package Pattonwebz_Framework
 * @since 1.0.0
 */

/**
 * Class that handles the base of a themes setup and initializations.
 */
class Pattonwebz_Framework {
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
	 * The path to the framework package - relative to theme path.
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
	public $sidebars = null;

	/**
	 * Used to store reference to the themes instantiated customizer class.
	 *
	 * @var object
	 */
	public $customizer = null;

	/**
	 * Holds the instance used to apply the themes action adds.
	 *
	 * @var object
	 */
	public $hooks = null;

	/**
	 * Holds the instance used to apply the themes action adds.
	 *
	 * @var object
	 */
	public $actions = null;

	/**
	 * Holds the instance used to apply the themes filter adds.
	 *
	 * @var object
	 */
	public $filters = null;

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

		// Add some helpers.
		$this->add_helpers();
		// Do theme setup actions.
		$this->do_theme_setup();
		// Add theme customizer, hooks, actions and filters.
		$this->add_hooks();
		$this->add_customizer_options();
		$this->add_actions_and_filters();
	}

	/**
	 * Add some useful helper functions for the theme.
	 */
	protected function add_helpers() {
		// maybe add some custom functions or sanitization filters here.
		include_once $this->theme_dir . $this->framework_relative_dir . 'extras/class-pattonwebz-framework-sanitizers.php';
	}

	/**
	 * Handle any theme setup actions.
	 */
	private function do_theme_setup() {

		// Register theme supported features adn set content width.
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
	private function register_nav_menus() {
		register_nav_menus(
			array(
				self::$prefix . 'primary'   => __( 'Primary Navigation', 'pattonwebz' ),
				self::$prefix . 'secondary' => __( 'Secondary Navigation', 'pattonwez' ),
			)
		);
	}

	/**
	 * Registers the dependancies used with setup of widget areas (sidebars).
	 */
	protected function register_widget_areas_deps() {
		// Class to register sidebars.
		include_once $this->theme_dir . $this->framework_relative_dir . 'widget-areas/class-pattonwebz-framework-widget-areas.php';
	}
	/**
	 * Adds widget areas for the theme.
	 *
	 * NOTE: Can be overriden and call parent::function to include the dependancy class.
	 */
	private function register_widget_areas() {
		$this->register_widget_areas_deps();
		// create a new instance of the class and store it in the sidebars_instance property.
		$this->sidebars = new PattonWebz_Framework_Widget_Areas();
	}

	/**
	 * Registers the dependancies needed to setup theme customizer instance.
	 */
	protected function add_customizer_options_deps() {
		// Base class to interface with wp_customizer object.
		include_once $this->theme_dir . $this->framework_relative_dir . 'customizer/class-pattonwebz-customizer.php';
	}
	/**
	 * Instatiate customizer class for theme.
	 *
	 * NOTE: Can be overriden and call parent::function to include the dependancy class.
	 */
	private function add_customizer_options() {

		// Store the customizer object in this property and pass the 3 paramiters.
		$this->customizer = new PattonWebz_Customizer( $this->theme_dir . $this->framework_relative_dir . 'customizer/', $this->theme_uri . $this->framework_relative_dir . 'customizer/', self::setting_defaults() );
	}

	/**
	 * Registers the features this theme supports that need declared.
	 *
	 * NOTE: Can be overriden and call parent::function to setup these basic supports.
	 */
	private function theme_support() {
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
	 * Registers the dependancies used to setup theme hooks.
	 */
	protected function add_hooks_deps() {
		// these classes contains the static methods that act as hooks to invoke actions.
		include_once $this->theme_dir . $this->framework_relative_dir . 'actions/class-pattonwebz-framework-hooks.php';
	}

	/**
	 * Adds theme hooks.
	 */
	private function add_hooks() {
		$this->add_hooks_deps();
		/**
		 * Instantiate this class and pass it a prefix to use to update the
		 * static prefix value inside the class.
		 */
		$this->hooks = new PattonWebz_Framework_Hooks();
	}

	/**
	 * Registers the dependancies used to setup theme actions and filters.
	 */
	protected function add_actions_and_filters_deps() {
		// Include the add_action and add_filter calls.
		include_once $this->theme_dir . $this->framework_relative_dir . 'actions/class-pattonwebz-framework-add-actions-or-filters.php';
	}

	/**
	 * Adds hooks actions and filters used by the theme in template files.
	 *
	 * NOTE: Can be overriden and call parent::function to include the dependancy class.
	 */
	private function add_actions_and_filters() {
		$this->add_actions_and_filters_deps;
		/**
		 * Create 2 instances of this base class for adding actions and filters.
		 *
		 *  NOTE: When overriding these will likely be different classes that
		 *  both extend the same parent class.
		 */
		$this->actions = new PattonWebz_Framework_Add_Actions_Or_Filters( self::$prefix );
		$this->filters = new PattonWebz_Framework_Add_Actions_Or_Filters( self::$prefix );
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
		 * Retain this block even when overriding.
		 *
		 * @var [type]
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
