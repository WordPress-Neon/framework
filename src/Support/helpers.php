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