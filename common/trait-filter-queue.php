<?php
/**
 * Trait containing common functionality for adding items to framework filter
 * queue (which includes actions).
 *
 * @package PattonWebz_Framework
 * @subpackage Framebase
 */

namespace PattonWebz\Framework\Common;

/**
 * This is responsible for the methods and properties for managing the classes
 * filter queues. It catches core WP add_action() and add_filter() functions
 * by redefining them so that that we can route them into into our own queue
 * for later execution.
 */
trait Filter_Queue {

	/**
	 * An array to hold all filters queued to be ran.
	 *
	 * @var array
	 */
	public $filters = array();

	/**
	 * Returns the array that holds the queue for filters.
	 *
	 * @return array
	 */
	public function get_filter_queue() {
		return $this->filters;
	}

	public function add_to_filter_queue( array $action = array() ) {
		error_log( 'add_to_filter_queue: ', 0 );
		error_log( print_r( $action, true), 0 );
		if ( ! is_empty( $action ) ) {
			$this->filters[] = $action;
		}
		return true;
	}
	/**
	 * This is a redefined function from WP core. It matches WP core exactly
	 * however here it routes to the traits add_filter() that is redefined from
	 * core.
	 *
	 * @param string   $tag              the hook handle to add under.
	 * @param callable $function_to_add  callback function to run on the hook.
	 * @param integer  $priority         execution priority.
	 * @param integer  $accepted_args    number of args accepted by the callback.
	 */
	public function add_action( string $tag = null, callable $function_to_add = null, $priority = 10, $accepted_args = 1 ) {
		error_log( 'add action overload', 0 );
		add_filter( $tag, $function_to_add, $priority, $accepted_args );
	}

	/**
	 * This is a redefined function from WP core. It reroutes any add_filter()
	 * calls and places queues them in the $filters array.
	 *
	 * @param string   $tag              the hook handle to add under.
	 * @param callable $function_to_add  callback function to run on the hook.
	 * @param integer  $priority         execution priority.
	 * @param integer  $accepted_args    number of args accepted by the callback.
	 */
	public function add_filter( string $tag = null, callable $function_to_add = null, $priority = 10, $accepted_args = 1 ) {
		error_log( 'add_filter overload', 0 );
		$action = array(
			'tag'      => $tag,
			'callback' => $function_to_add,
			'priority' => $priority,
			'args'     => $accepted_args,
		);
		$this->add_to_filter_queue( $action );
		return true;
	}


}
