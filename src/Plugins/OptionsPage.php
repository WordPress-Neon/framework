<?php

namespace WPN\Plugins;

use WPN\Support\Stringable;
use WPN\Support\Traits\RegistersPlugin;

class OptionsPage implements PluginInterface {
	use RegistersPlugin;

	public function __invoke() {
		acf_add_options_page( array(
			'page_title' => 'Theme Settings',
			'menu_title' => 'Theme Settings',
			'menu_slug'  => 'theme-settings',
			'capability' => 'manage_options',
			'redirect'   => false,
			'position'   => 2,
			'autoload'   => true,
			'icon_url'   => $this->config->icon ?? asset_path( 'favicon.svg' )
		) );

		add_action( 'admin_head', function () { ?>
            <style>
                .toplevel_page_theme-settings img {
                    width: 20px;
                }
            </style>
		<?php } );
	}

	public static function submenu( string $title ): void {
		acf_add_options_sub_page( array(
			'menu'       => $title,
			'title'      => $title,
			'slug'       => Stringable::slug( $title ),
			'parent'     => 'theme-settings',
			'capability' => 'manage_options'
		) );
	}
}