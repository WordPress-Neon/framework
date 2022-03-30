<?php

namespace WPN\Plugins;

use WPN\Support\App;
use WPN\Support\Traits\RegistersPlugin;

class Mailhog implements PluginInterface {
	use RegistersPlugin;

	public function __invoke() {
		if ( str_contains( get_site_url(), 'localhost' ) ) {
			add_action( 'phpmailer_init', function ( $php_mailer ) {
				$php_mailer->Host     = 'mailhog';
				$php_mailer->Port     = 1025;
				$php_mailer->From     = 'wordpress@localhost.test';
				$php_mailer->FromName = 'WordPress';
				$php_mailer->IsSMTP();
			}, 10 );

			add_filter( 'wp_mail_content_type', fn() => 'text/html' );

			add_filter( 'wp_mail_from', fn( $email ) => 'wordpress@localhost.test' );

			add_filter( 'wp_mail_from_name', fn( $name ) => 'localhost' );
		}
	}
}