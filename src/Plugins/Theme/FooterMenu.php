<?php

namespace WPN\Plugins\Theme;

use WPN\Support\Traits\RegistersThemeFeature;

class FooterMenu {
	use RegistersThemeFeature;

	public function __construct() {
		add_action( 'init', function () {
			register_nav_menus(
				array(
					'footer-menu' => __( 'Footer Menu' )
				)
			);
		} );
	}
}