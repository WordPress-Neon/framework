<?php

namespace WPN\Support\Api;

use WP_REST_Request;

abstract class Middleware implements AuthenticatesRequests {
	abstract public function authenticate( WP_REST_Request $request ): bool;
}