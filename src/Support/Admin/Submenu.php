<?php

namespace WPN\Support\Admin;

use WPN\Support\Stringable;
use Closure;

class Submenu {
	public static function register( string $name, string $parent, string|Closure|bool $callback = false ): void {
		add_action( 'admin_menu', function () use ( $name, $parent, $callback ) {
			$callback_function = false;
			if ( $callback ) {
				$callback_function = is_callable( $callback ) ? $callback : function () use ( $callback ) {
					( new $callback )();
				};
			};
			add_submenu_page(
				Stringable::slug( $parent ),
				$name,
				$name,
				'manage_options',
				Stringable::slug( $name ),
				$callback_function
			);
		}, 20 );
	}
}