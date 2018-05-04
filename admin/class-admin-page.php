<?php

namespace PattonWebz\Framework\Admin;

class Admin_Page implements Theme_Admin_Page {

	use \PattonWebz\Framework\Common\Prefix;

	/**
	 * Should hold an instance of a WP_Theme object.
	 *
	 * @see: https://codex.wordpress.org/Class_Reference/WP_Theme
	 *
	 * @var object
	 */
	public $theme_info = null;

	public function __construct( $prefix = null ) {
		// if we have a prefix redefine it.
		if ( null !== $prefix ) {
			// cast the prefix to a string incase of incorrect item passed.
			$this->prefix = (string) $prefix;
		}
		// sets the theme info to be a WP_Theme object of current theme.
		$this->theme_info = wp_get_theme();

	}

	public function hook_pages() {
		add_action( 'admin_menu', array( $this, 'hook_pages_callback' ) );
	}

	public function hook_pages_callback() {
		$title = $this->theme_info->name;
		$icon  = 'dashicon-theme';
		add_submenu_page( 'themes.php', $title, $title, 'manage_options', sanitize_title_with_dashes( $title ), array( $this, 'page_render' ) );

	}

	public function page_render() {
		$html = $this->get_page_contents();

		global $allowed_tags;
		echo wp_kses( $html, $allowed_tags );
	}
	private function get_page_contents() {
		?>
		<div class="wrap about-wrap full-width-layout">
			<h1><?php echo esc_html( $this->theme_info->title ); ?></h1>
			<p class="about-text"><?php echo esc_html( $this->theme_info->description ); ?></p>
			<?php echo $this->get_page_contents_upper(); // wpcs: XSS ok. ?>
		</div>
		<?php
	}
	private function get_page_contents_upper() {
		global $allowed_tags;
		?>
		<div class="feature-section one-col">
			<div class="col">
				<?php
				// translators: 1 - theme title, 2 - an emojie heart image, 3 - html break tag.
				$header_text = sprintf( esc_html__( '%1$1s Is Built With %2$2s Using The %3$3s PattonWebz Theme Framework', 'pattonwebz' ),
					esc_html( $this->theme_info->name, 'pattonwebz' ),
					'<img draggable="false" class="emoji" alt="❤" src="https://s.w.org/images/core/emoji/2.4/svg/2764.svg">',
					'<br>'
				);
				?>
				<h2><?php echo $header_text; // wpcs: XSS ok. ?></h2>
				<p><?php esc_html_e( 'The framework is intended to provide setup actions and basic defaults for a theme. It does this through extendable classes, interfaces and traits that can be utulised in a child theme - or a parent theme including the framework directly.', 'pattonwebz' ); ?></p>
			</div>
		</div>
		<hr>
		<div class="info-cols">
			<h2><?php esc_html_e( 'Framework Info', 'pattonwebz' ); ?> <img draggable="false" class="emoji" alt="🔧" src="https://s.w.org/images/core/emoji/2.4/svg/1f527.svg"></h2>
			<div class="two-col">
				<div class="col">
					<h3><?php esc_html_e( 'Help Support:', 'pattonwebz' ); ?></h3>
					<p><?php esc_html_e( 'Support for this theme is likely provided by the theme author:', 'pattonwebz' ); ?> <?php echo wp_kses( $this->theme_info->author, $allowed_tags ); ?></p>
					<p><?php esc_html_e( 'Framework support can be found at the repo at github:', 'pattonwebz' ); ?> <a href="https://github.com/pattonwebz/theme-framework/"><?php esc_html_e( 'PattonWebz Framework', 'pattonwebz' ); ?></a>.</p>
				</div>
				<div class="col">
					<h3><?php esc_html_e( 'Framework Ideals:', 'pattonwebz' ); ?></h3>
					<p><?php esc_html_e( 'Not opinionated about how you build your theme templates.', 'pattonwebz' ); ?> <strong><?php esc_html__( 'Templates remain firmly in your court.', 'pattonwebz' ); ?></strong> <?php esc_html__( 'Make them how you wish, there is no special template engines in use.', 'pattonwebz' ); ?><p>
					<p><?php esc_html_e( "It should be easily instantiated and extended - while at the same time be set-it-and-forget-it if that's how the author wants to use it.", 'pattonwebz' ); ?></p>
				</div>
				<div class="col">
					<h3><?php esc_html_e( 'PHP Compatibility Notes:', 'pattonwebz' ); ?></h3>
					<p><?php esc_html_e( "I've built this framework with code that is not compatible with very old versions of PHP. It should run on PHP as old as 5.4 but no effort is made to keep it backwards compatible with anything prior to PHP version 5.6.", 'pattonwebz' ); ?></p>
					<p><?php esc_html_e( 'Nothing beofore PHP 5.6 recieves security updates and even that reaches end of life on 31 Dec 2018 where no farther security updates will be applied.', 'pattonwebz' ); ?></p>
					<p><?php esc_html_e( 'Read more about why updating php to a recent version is a good thing (but since this theme activated you are already on one):', 'pattonwebz' ); ?> <a href="https://wordpress.org/support/upgrade-php/"><?php esc_html__( 'WordPress Upgrade PHP', 'pattonwebz' ); ?></a></p>
				</div>
			</div>
		</div>
		<?php
	}
}
