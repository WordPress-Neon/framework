<?php

namespace WPN\Support\Api;

use WP_REST_Request;

class DefaultMiddleware implements AuthenticatesRequests {
	public function authenticate( WP_REST_Request $request ): bool {
		return true;
	}
}