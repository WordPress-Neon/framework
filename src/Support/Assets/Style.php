<?php

namespace WPN\Support\Assets;

use WPN\Support\App;
use WPN\Support\Stringable;

class Style {
	public static function load( string $file, bool $admin = false ): void {
		add_action( $admin ? 'admin_enqueue_scripts' : 'wp_enqueue_scripts', function () use ( $file ) {
			wp_enqueue_style(
				Stringable::slug( $file ),
				get_stylesheet_directory_uri() . '/css/' . $file,
				[],
				App::environment( 'production' ) ? time() : false
			);
		} );
	}
}