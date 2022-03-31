<?php

namespace WPN\Assets;

use WPN\App;
use WPN\Support\Stringable;

class Style {
	public static function load( string $file, bool $admin = false ): void {
		add_action( $admin ? 'admin_enqueue_scripts' : 'wp_enqueue_scripts', function () use ( $file ) {
			wp_enqueue_style(
				Stringable::slug( $file ),
				asset_path( $file ),
				[],
				App::environment( 'production' ) ? filemtime( get_template_directory() . "/$file" ) : time()
			);
		} );
	}
}