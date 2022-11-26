<?php

namespace WPN\Assets;

use WPN\App;
use WPN\Support\Stringable;

class GoogleFont {
	public static function load( string $name, string $file, bool $admin = false ): void {
		add_action( 'wp_enqueue_scripts', function () use ( $file, $name ) {
			static::enqueue( $file, $name );
		} );
		
		if ( $admin ) {
			add_action( 'admin_enqueue_scripts', function () use ( $file, $name ) {
				static::enqueue( $file, $name );
			} );
		}
	}

	private static function enqueue( $file, $name ): void {
		wp_enqueue_style(
			Stringable::slug( $name ),
			$file,
			[],
			App::environment( 'production' ) ? time() : false
		);
	}
}