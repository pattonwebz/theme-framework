<?php

namespace PattonWebz\Framework\Admin;

interface Theme_Admin_Page {

	public function __construct( $prefix = null );

	public function hook_pages();

	public function page_render();
}
