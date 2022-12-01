<?php

use WPN\App;

if ( ! function_exists( 'asset_path' ) ) {
	function asset_path( string $path = '' ): string {
		return trailingslashit( app()->assetPath() ) . $path;
	}
}

if ( ! function_exists( 'app' ) ) {
	function app(): App {
		return apply_filters( 'wpn_app', new App() );
	}
}

if ( ! defined( 'SECONDS_IN_AN_HOUR' ) ) {
	define( 'SECONDS_IN_AN_HOUR', 3600 );
}

if ( ! defined( 'SECONDS_IN_A_DAY' ) ) {
	define( 'SECONDS_IN_A_DAY', 86400 );
}

if ( ! defined( 'SECONDS_IN_A_WEEK' ) ) {
	define( 'SECONDS_IN_A_WEEK', 604800 );
}