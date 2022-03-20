<?php

namespace WPN\Support;

class PostType {
	public function __construct(
		private string $name
	) {
	}

	public function taxonomy( string $name, array $args = [], bool $private = false ): self {
		add_action( 'init', function () use ( $name, $args, $private ) {
			register_taxonomy( Stringable::slug( $name ), Stringable::slug( $this->name ), [
				'labels'            => [
					'name' => $name,
				],
				'public'            => ! $private,
				'hierarchical'      => true,
				'show_ui'           => true,
				'show_in_menu'      => true,
				'show_in_rest'      => true,
				'show_in_nav_menus' => true,
				'show_admin_column' => true,
				'query_var'         => ! $private,
				'show_in_admin_bar' => ! $private,
				...$args
			] );
		}, 10 );

		return $this;
	}

	public static function register( string $name, array $supports = [], array $args = [], bool $private = false ): PostType {
		add_action( 'init', function () use ( $name, $private, $args, $supports ) {
			$args = [
				'label'               => $name,
				'labels'              => [ 'name' => $name ],
				'public'              => ! $private,
				'hierarchical'        => false,
				'description'         => '',
				'supports'            => [
					'title',
					'thumbnail',
					...$supports,
				],
				'show_ui'             => true,
				'show_in_menu'        => true,
				'show_in_rest'        => false,
				'show_in_nav_menus'   => true,
				'show_in_admin_bar'   => ! $private,
				'has_archive'         => ! $private,
				'can_export'          => true,
				'exclude_from_search' => $private,
				'publicly_queryable'  => ! $private,
				'query_var'           => ! $private,
				'capability_type'     => 'post',
				...$args,
			];
			register_post_type( Stringable::slug( $name ), $args );
		}, 20 );

		if ( array_key_exists( 'menu_icon', $args ) && str_contains( $args['menu_icon'], '.' ) ) {
			add_action( 'admin_head', function () use ( $name ) { ?>
                <style>
                    #menu-posts-<?= Stringable::slug( $name ); ?> .wp-menu-image img {
                        width: 20px;
                    }
                </style>
			<?php } );
		}

		return new PostType( $name );
	}
}