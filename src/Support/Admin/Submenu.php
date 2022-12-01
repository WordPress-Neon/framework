<?php

namespace WPN\Support\Admin;

use WPN\Exceptions\WPNInvalidCoreMenuException;
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

	/**
	 * @throws WPNInvalidCoreMenuException
	 */
	public static function core(
		string $core_menu,
		string $title,
		string|Closure $callback,
		string $capability = 'install_plugins'
	): void {
		if ( ! is_callable( "add_{$core_menu}_page" ) ) {
			throw new WPNInvalidCoreMenuException( 'Invalid core menu' );
		}

		add_action( 'admin_menu', function () use ( $core_menu, $title, $capability, $callback ) {
			call_user_func( "add_{$core_menu}_page",
				$title,
				$title,
				$capability,
				Stringable::slug( $title ),
				is_callable( $callback ) ? $callback : function () use ( $callback ) {
					return ( new $callback )();
				}
			);
		} );
	}
}