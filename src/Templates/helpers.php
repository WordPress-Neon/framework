<?php

use WPN\App;

if ( ! function_exists( 'get_component' ) ) {
	function get_component( string $path, array|null $variables = null ): string {
		$file = get_template_directory() . '/' . trailingslashit( App::templatePartDirectory() ) . $path . '.php';
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
		get_template_part( trailingslashit( App::templatePartDirectory() ) . $path, null, $variables );
	}
}
