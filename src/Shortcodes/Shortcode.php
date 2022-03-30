<?php

namespace WPN\Shortcodes;

use Closure;

class Shortcode {
	use RegistersShortcode;

	public static function register( string $handle, string|Closure $callback ): void {
		( new self() )->registerShortcode( $handle );

		$callback_function = is_callable( $callback ) ? $callback : fn( $args ) => ( new $callback )( $args );

		add_shortcode( $handle, $callback_function );
	}
}
