<?php

namespace PattonWebz\Framework;

/**
 * Class is responsible for adding the callback functions to actions.
 *
 * NOTE: Hold onto this instance so that these can be unhooked at a later point.
 */
class Actions extends Add_Actions_Or_Filters {

	/**
	 * Calling this method hooks the actions in.
	 */
	public function init() {
		add_action( 'pattonwebz_do_layout_selection', array( $this, 'output_layout_classnames' ), 10, 2 );
		add_action( 'pattonwebz_do_after_title', array( $this, 'output_post_meta' ), 10, 1 );
		add_action( 'pattonwebz_do_after_title', array( $this, 'output_single_pagination' ), 10, 1 );
		add_action( 'pattonwebz_do_after_content', array( $this, 'output_post_tags' ), 20, 1 );
		add_action( 'pattonwebz_do_after_content', array( $this, 'output_link_pages' ), 10, 1 );
	}

	/**
	 * Returns or echos a classname string for use defining theme layouts.
	 *
	 * @param  string  $classname_string any classnames to be output.
	 * @param  boolean $echo             flag to decide when to echo or return.
	 *
	 * @return string
	 */
	public function output_layout_classnames( string $classname_string, $echo = true ) {
		// either echo or return any passed classnames.
		if ( $echo ) {
			// echo classnames inside a `class=""` attribute`.
			echo 'class="' . esc_attr( apply_filters( 'leadnaut_filter_layout_classnames', $classname_string ) ) . '"';
		} else {
			// return just the classnames.
			return apply_filters( 'leadnaut_filter_layout_classnames', $classname_string );
		}
	}

	/**
	 * Returns or echos some entry meta based on conditionals.
	 * NOTE: Will fail silently.
	 *
	 * @param string $type string indicating type of meta we want, can be FALSE.
	 *
	 * @return string/void Can return either a string or nothing if echoing.
	 */
	public function output_post_meta( $type = false ) {
		// if no specific type was requested, figure it out.
		// NOTE: due to a quirk of using a front-page.php template `is_home()` and
		// `is_front_page()` are true when no static page is set. Test that first.
		if ( ! $type ) {
			if ( is_home() && is_front_page() ) {
				$type = 'front-page';
			} elseif ( is_home() ) {
				$type = 'home';
			} elseif ( is_front_page() ) {
				$type = 'front-page';
			} elseif ( is_single() && ! is_singular( 'page' ) ) {
				$type = 'single';
			} elseif ( is_singular( 'page' ) ) {
				$type = 'page';
			}
		}
		$output = '';
		// if $type is single OR no type is passed and it is single but not a page...
		if ( 'single' === $type ) {
			ob_start(); ?>
			<div class="meta">
				<span class="entry-meta text-muted">
					<?php
					ob_start();
					the_category( esc_html__( ' and ', 'pattonwebz' ) );
					$categories = ob_get_clean();
					/* translators: 1 = author name, 2 = date, 3 = list of categories.*/
					$meta = sprintf( __( 'Written by %1$s on %2$s and posted in %3$s.', 'pattonwebz' ),
						get_the_author_link(),
						get_the_time( get_option( 'date_format' ) ),
						$categories
					);
					echo wp_kses_post( $meta );
					?>
				</span>
			</div>
			<?php
			$output = ob_get_clean();
		} elseif ( 'front-page' === $type ) {
			ob_start();
			?>
			<footer class="meta">
				<span class="entry-meta">
					<?php the_time( get_option( 'date_format' ) ); ?> &#8226;
					<a href="<?php comments_link(); ?>" title="<?php comments_number( 'No Comments', 'One Comment', '% Comments' ); ?>">
						<?php comments_number( 'No Comments', 'One Comment', '% Comments' ); ?>
					</a>
				</span>
			</footer>
			<?php
			$output = ob_get_clean();
		} elseif ( 'page' ) {
			$output = '<hr>';
		} else {
			ob_start();
			?>
			<footer class="meta">
				<span class="entry-meta">
					<?php esc_html_e( 'Written by', 'pattonwebz' ); ?> <?php the_author_link(); ?>
					<?php esc_html_e( 'on', 'pattonwebz' ); ?> <?php the_time( get_option( 'date_format' ) ); ?>
					<?php esc_html_e( 'in', 'pattonwebz' ); ?> <?php the_category( ' and ' ); ?>
					<?php esc_html_e( 'with', 'pattonwebz' ); ?> <a href="<?php comments_link(); ?>" title="<?php comments_number( 'no comments', 'one comment', '% comments' ); ?>"><?php comments_number( 'no comments', 'one Comment', '% comments' ); ?></a>
				</span>
			</footer>
			<hr>
			<?php
			$output = ob_get_clean();
		} // End if().

		// either return or echo the output.
		echo wp_kses_post( apply_filters( 'leadnaut_post_meta_output', $output ) );
		return apply_filters( 'leadnaut_post_meta_output', $output );
	}

	/**
	 * Output a next/previous link list on non 'page' single items.
	 */
	public static function output_single_pagination() {
		if ( is_single() && ! is_singular( 'page' ) ) {
			?>
			<ul class="prev-next-single pager clearfix">
				<li class="previous"><?php previous_post_link( '%link', '&larr; ' . esc_html__( 'Previous Post', 'pattonwebz' ) ); ?></li>
				<li class="next"><?php next_post_link( '%link', esc_html__( 'Next Post', 'pattonwebz' ) . ' &rarr;' ); ?></li>
			</ul>
			<?php
		}
	}

	/**
	 * Outputs post tags. Echos directly to page.
	 */
	public static function output_post_tags() {
		the_tags( '<span class="post-tags"><span class="meta">' . esc_html__( 'Tags: ', 'pattonwebz' ) . '</span> ', ' ', '</span>' );
	}

	/**
	 * Outputs any page links between splits.
	 */
	public static function output_link_pages() {
		wp_link_pages( array(
			'before' => '<hr class="hr-row-divider"><p class="wp-link-pages hero-p">' . esc_html__( 'Continue Reading: ', 'pattonwebz' ),
			'after'  => '</p>',
		));
	}
}
