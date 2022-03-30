<?php

namespace WPN\Ajax;

class Response {
	public function __construct( array $data ) {
		wp_die( json_encode( $data ) );
	}
}