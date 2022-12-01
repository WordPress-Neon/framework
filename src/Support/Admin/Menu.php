<?php

namespace WPN\Support\Admin;

use WPN\Support\Stringable;
use Closure;

class Menu {
	public function __construct(
		protected string $name,
	) {
	}

	public static function register(
		string $name,
		string|Closure $callback,
		int $position = 0,
		string $icon = 'dashicons-admin-tools'
	): self {
		add_action( 'admin_menu', function () use ( $name, $callback, $icon, $position ) {
			add_menu_page(
				$name,
				$name,
				'manage_options',
				Stringable::slug( $name ),
				is_callable( $callback ) ? $callback : function () use ( $callback ) {
					return ( new $callback )();
				},
				$icon,
				$position
			);
		} );

		if ( str_contains( $icon, '.' ) ) {
			add_action( 'admin_head', function () use ( $name ) { ?>
                <style>
                    .toplevel_page_<?= Stringable::slug($name); ?> img {
                        width: 20px;
                    }
                </style>
			<?php } );
		}

		return new self( $name );
	}

	public function submenu( string $name, Closure|string $callback ): self {
		Submenu::register( $name, Stringable::slug( $this->name ), $callback );

		return $this;
	}
}