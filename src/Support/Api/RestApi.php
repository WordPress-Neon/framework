<?php

namespace WPN\Support\Api;

use Closure;

class RestApi {
	public static function route( string $route, string $method, string|Closure $callback, Middleware $middleware ) {
		$callback = is_callable( $callback ) ? $callback : function () use ( $callback ) {
			( new $callback() )();
		};

		register_rest_route( 'theme/v1', $route, array(
			'methods'             => $method,
			'callback'            => $callback,
			'permission_callback' => fn() => ( new $middleware )(),
		) );
	}
}