<?php

namespace WPN\Http;

class Response {
	public function __construct( array $data, $status = 200 ) {
		wp_die( json_encode( compact( 'data', 'status' ) ) );
	}
}