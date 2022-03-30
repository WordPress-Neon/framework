<?php

namespace WPN\Assets;

use WPN\App;
use WPN\Support\Stringable;

class Script {
	public function __construct(
		private string $handle
	) {
	}

	public function localize( string $object_name, array $data ): self {
		add_action( 'wp_enqueue_scripts', function () use ( $object_name, $data ) {
			wp_localize_script( $this->handle, $object_name, $data );
		}, 200 );

		return $this;
	}

	public static function ajaxData(): array {
		return [
			'nonce'   => wp_create_nonce( 'theme-nonce' ),
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
		];
	}

	public static function load( string $file, bool $admin = false ): Script {
		add_action( $admin ? 'admin_enqueue_scripts' : 'wp_enqueue_scripts', function () use ( $file ) {
			wp_enqueue_script(
				Stringable::slug( $file ),
				asset_path( $file ),
				[],
				App::environment( 'production' ) ? time() : false
			);
		}, 90 );

		return new Script( Stringable::slug( $file ) );
	}
}