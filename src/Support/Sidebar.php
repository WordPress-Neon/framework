<?php

namespace WPN\Support;

class Sidebar {
	public static function register( string $name, array $args = [] ): void {
		add_action( 'widgets_init', function () use ( $name, $args ) {
			register_sidebar( [
				'name'          => $name,
				'id'            => Stringable::slug( $name ),
				'before_title'  => '<h5 class="sidebar-heading">',
				'after_title'   => '</h5>',
				'before_widget' => '<div class="text-sm">',
				'after_widget'  => '</div>',
				...$args,
			] );
		}, 10 );
	}

	public static function get( string $sidebar_name ): void {
		if ( is_active_sidebar( Stringable::slug( $sidebar_name ) ) ) {
			dynamic_sidebar( Stringable::slug( $sidebar_name ) );
		}
	}
}