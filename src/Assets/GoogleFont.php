<?php

namespace WPN\Support\Assets;

use WPN\App;
use WPN\Support\Stringable;

class GoogleFont {
	public static function load( string $name, string $file, bool $admin = false ): void {
		add_action( $admin ? 'admin_enqueue_scripts' : 'wp_enqueue_scripts', function () use ( $file, $name ) {
			wp_enqueue_style(
				Stringable::slug( $name ),
				$file,
				[],
				App::environment( 'production' ) ? time() : false
			);
		} );
	}
}