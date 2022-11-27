<?php

namespace WPN\Support;

class Stringable {
	public static function slug( string $string ): string {
		return sanitize_title( $string );
	}

	public static function headline( string $string ): string {
		$parts = explode( ' ', $string );

		$parts = count( $parts ) > 1
			? array_map( [ static::class, 'title' ], $parts )
			: array_map( [ static::class, 'title' ], static::ucsplit( implode( '_', $parts ) ) );

		$collapsed = static::replace( [ '-', '_', ' ' ], '_', implode( '_', $parts ) );

		return implode( ' ', array_filter( explode( '_', $collapsed ) ) );
	}

	public static function title( string $value ): string {
		return mb_convert_case( $value, MB_CASE_TITLE, 'UTF-8' );
	}

	public static function replace(
		string|array $search,
		string|array $replace,
		string|array $subject
	): string {
		return str_replace( $search, $replace, $subject );
	}

	public static function ucsplit( string|array $string ): array|false {
		return preg_split( '/(?=\p{Lu})/u', $string, - 1, PREG_SPLIT_NO_EMPTY );
	}
}