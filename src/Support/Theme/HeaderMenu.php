<?php

namespace WPN\Support\Theme;

use WPN\Support\Traits\RegistersThemeFeature;

class HeaderMenu {
	use RegistersThemeFeature;

	public function __construct() {
		add_action( 'init', function () {
			register_nav_menus(
				array(
					'header-menu' => __( 'Header Menu' )
				)
			);
		} );

		add_filter( 'nav_menu_css_class', fn( array $classes ) => [ ...$classes, 'font-bold' ], 10, 1 );
	}
}