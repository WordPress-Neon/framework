<?php

if ( ! function_exists( 'get_component' ) ) {
	function get_component( string $path, array|null $variables = null ): string {
		$filetypes = array(
			'.php',
		);
		foreach ( $filetypes as $type ) {
			$file   = get_template_directory() . '/views/components/' . $path . $type;
			$output = null;
			if ( file_exists( $file ) ) :
				if ( $variables ) :
					extract( $variables );
				endif;
				ob_start();
				include $file;

				return ob_get_clean();
			endif;
		}

		return false;
	}
}

if ( ! function_exists( 'render_component' ) ) {
	function render_component( string $path, array|null $variables = null ): void {
		get_template_part( 'views/components/' . $path, null, $variables );
	}
}

if ( ! function_exists( 'asset_path' ) ) {
	function asset_path( string $path = '' ): string {
		return get_stylesheet_directory_uri() . '/img/' . $path;
	}
}
