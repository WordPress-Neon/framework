<?php

if ( ! function_exists( 'get_component' ) ) {
	function get_component( string $path, array|null $variables = null ): string {
		$file = get_template_directory() . '/template-parts/' . $path . '.php';
		if ( $variables ) :
			extract( $variables );
		endif;
		ob_start();
		include $file;

		return ob_get_clean();
	}
}

if ( ! function_exists( 'render_component' ) ) {
	function render_component( string $path, array|null $variables = null ): void {
		get_template_part( 'template-parts/' . $path, null, $variables );
	}
}

if ( ! function_exists( 'asset_path' ) ) {
	function asset_path( string $path = '' ): string {
		return get_stylesheet_directory_uri() . '/img/' . $path;
	}
}
