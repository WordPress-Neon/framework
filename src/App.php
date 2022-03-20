<?php

namespace WPN;

class App {
	protected string $config_path = '';
	protected static string $asset_path = 'assets';
	protected static string $template_path = 'template-parts';

	public function __construct() {
	}

	public function init(): void {
		add_filter( 'show_admin_bar', fn() => false );

		$this->disableFileEditing();

		add_theme_support( 'post-thumbnails' );

		if ( ! file_exists( $this->config_path ) ) {
			return;
		}

		$config = require $this->config_path;

		self::registerPlugins( $config );
		self::registerFeatures( $config );
	}

	protected function disableFileEditing(): self {
		add_action( 'init', function () {
			define( 'DISALLOW_FILE_EDIT', true );
		} );

		return $this;
	}

	public static function assetPath(): string {
		return get_stylesheet_directory_uri() . static::$asset_path;
	}

	public static function templatePartDirectory(): string {
		return static::$template_path;
	}

	public static function environment( string $environment ): bool {
		if ( $environment == 'local' ) {
			return WP_DEBUG || str_contains( get_site_url(), 'localhost' );
		}

		if ( $environment == 'production' ) {
			return ! WP_DEBUG || ! str_contains( get_site_url(), 'localhost' );
		}

		return false;
	}

	private static function registerPlugins( array $config ): void {
		if ( array_key_exists( 'plugins', $config ) ) {
			foreach ( $config['plugins'] as $plugin => $settings ) {
				$registered_plugin = is_string( $plugin ) ? $plugin : $settings;
				( new $registered_plugin )->register( $config['plugins'][ $registered_plugin ] ?? [] );
			}
		}
	}

	private static function registerFeatures( array $config ): void {
		if ( array_key_exists( 'features', $config ) ) {
			foreach ( $config['features'] as $feature ) {
				( new $feature )->register();
			}
		}
	}
}