<?php

namespace WPN\Support\Api;

use WP_REST_Request;

interface AuthenticatesRequests {
	public function authenticate( WP_REST_Request $request ): bool;
}