<?php

namespace WPN\Support\Api;

use WP_REST_Request;
use WP_REST_Response;

interface RestResponse {
	public function __invoke( WP_REST_Request $request ): WP_REST_Response;
}