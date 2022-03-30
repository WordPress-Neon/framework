<?php

namespace WPN\Support\Plugins;

use WPN\Support\Traits\RegistersPlugin;

class SVGSupport implements Plugin {
	use RegistersPlugin;

	public function __invoke() {
		add_filter( 'wp_check_filetype_and_ext', function ( $data, $file, $filename, $mimes ) {

			global $wp_version;
			if ( $wp_version !== '4.7.1' ) {
				return $data;
			}

			$filetype = wp_check_filetype( $filename, $mimes );

			return [
				'ext'             => $filetype['ext'],
				'type'            => $filetype['type'],
				'proper_filename' => $data['proper_filename']
			];

		}, 10, 4 );

		add_filter( 'upload_mimes', function ( $mimes ) {
			$mimes['svg'] = 'image/svg+xml';

			return $mimes;
		} );

		add_action( 'admin_head', function () {
			echo '<style type="text/css">
        .attachment-266x266, .thumbnail img {
             width: 100% !important;
             height: auto !important;
        }
        </style>';
		} );
	}
}