<?php

namespace WPN\Support\Api;

use Closure;
use ReflectionClass;

class RestApi {
	/**
	 * @param  string  $route
	 * @param  string  $method
	 * @param  string|Closure  $callback
	 * @param  array[]  $params
	 * @param  string|Closure  $middleware
	 *
	 * @return void
	 */
	public static function route(
		string $route,
		string $method,
		string|Closure $callback,
		array $params = [],
		string|Closure $middleware = DefaultMiddleware::class
	) {
		$callback            = static::middleware_callback( $callback );
		$middleware_callback = static::middleware_callback( $middleware );
		$args                = static::get_args_from_params( $params );

		add_action( 'rest_api_init', function () use (
			$route,
			$method,
			$callback,
			$args,
			$middleware_callback
		) {
			register_rest_route( 'theme/v1', $route, array(
				'methods'             => $method,
				'callback'            => $callback,
				'permission_callback' => $middleware_callback,
				'args'                => $args
			) );
		} );
	}

	protected static function get_args_from_params( array $params ): array {
		$formatted_args = [];
		foreach ( $params as $field => $args ) {
			$formatted_args[ $field ] = [
				'required'          => $args['required'] ?? false,
				'validate_callback' => $args['callback'],
			];
		}

		return $formatted_args;
	}

	protected static function callback( string|Closure $callback ): Closure {
		return is_callable( $callback ) ? $callback : function ( $request ) use ( $callback ) {
			return ( new $callback() )( $request );
		};
	}

	protected static function middleware_callback( string|Closure $middleware ): Closure {
		return is_callable( $middleware ) ? $middleware : function ( $request ) use ( $middleware ) {
			$reflection = new ReflectionClass( $middleware );

			return $reflection->hasMethod( 'authenticate' ) ? ( new $middleware() )->authenticate( $request ) : ( new $middleware() )( $request );
		};
	}
}