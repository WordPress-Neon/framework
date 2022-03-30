<?php

namespace WPN\Support\Ajax;

class Route {
	public static function registerRoutes( array $routes, bool $no_priv = true ) {
		foreach ( $routes as $name => $callback ) {
			self::create( $name, $callback, true );
		}
	}

	public static function create( string $name, string $callback, bool $nopriv = true ): void {
		$callback = function () use ( $callback ) {
			if ( ! wp_verify_nonce( $_POST['security'], 'theme-nonce' ) ) {
				wp_die( '419' );
			}
			( new $callback() )();
		};

		add_action( "wp_ajax_$name", $callback );

		if ( $nopriv ) {
			add_action( "wp_ajax_nopriv_$name", $callback );
		}
	}
}