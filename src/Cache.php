<?php

namespace WPN;

use Closure;
use WPN\Interfaces\CacheInterface;

class Cache implements CacheInterface {
	public static function remember( string $key, int $ttl, Closure $closure ): mixed {
		if ( false === ( $content = get_transient( $key ) ) ) {
			$content = $closure();
			set_transient( $key, $content, $ttl );
		}

		return $content;
	}

	public static function rememberForever( string $key, Closure $closure ): mixed {
		if ( false === ( $content = get_transient( $key ) ) ) {
			$content = $closure();
			set_transient( $key, $content, 0 );
		}

		return $content;
	}

	public static function delete( string $key ): bool {
		return delete_transient( $key );
	}

	public static function get( string $key ): mixed {
		return get_transient( $key );
	}

	public static function set( string $key, mixed $value, int $ttl = 0 ): mixed {
		set_transient( $key, $value, $ttl );

		return $value;
	}
}