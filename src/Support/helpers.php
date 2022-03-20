<?php

use WPN\App;

if ( ! function_exists( 'asset_path' ) ) {
	function asset_path( string $path = '' ): string {
		return trailingslashit( App::assetPath() ) . $path;
	}
}