<?php

namespace WPN\Interfaces;

use Closure;

interface CacheInterface {
	public static function remember( string $key, int $ttl, Closure $closure ): mixed;

	public static function rememberForever( string $key, Closure $closure ): mixed;

	public static function delete( string $key ): bool;

	public static function get( string $key ): mixed;

	public static function set( string $key, mixed $value, int $ttl = 0 ): mixed;
}