<?php

namespace WPN;

use WPN\Exceptions\WPNInitializationException;

final class App {
	public string $template_path = '';
	public string $asset_path = '';

	/**
	 * @throws WPNInitializationException
	 */
	public function init( string $config_path = '' ): self {
		$config_path = $config_path ?? get_template_directory() . '/inc/config.php';

		add_filter( 'show_admin_bar', fn() => false );

		$this->disableFileEditing();

		add_theme_support( 'post-thumbnails' );

		if ( ! file_exists( $config_path ) ) {
			throw new WPNInitializationException( 'Unable to find config file' );
		}

		$config = require $config_path;

		$this->template_path = $config['template_path'] ?? 'template-parts';
		$this->asset_path    = $config['asset_path'] ?? 'assets';

		self::registerPlugins( $config );
		self::registerFeatures( $config );
		self::registerProviders( $config );

		add_filter( 'wpn_app', fn() => $this );

		return $this;
	}

	protected function disableFileEditing(): self {
		add_action( 'init', function () {
			define( 'DISALLOW_FILE_EDIT', true );
		} );

		return $this;
	}

	public function assetPath(): string {
		return get_stylesheet_directory_uri() . '/' . $this->asset_path;
	}

	public function templatePartDirectory(): string {
		return $this->template_path;
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

	private static function registerProviders( array $config ): void {
		if ( array_key_exists( 'providers', $config ) ) {
			foreach ( $config['providers'] as $provider ) {
				( new $provider() );
			}
		}
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