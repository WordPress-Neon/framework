<?php

namespace WPN\Validation;

use DateTimeInterface;
use Exception;

class Validator {
	public function validate( mixed $value, string|callable $method ): bool {
		return is_string( $method )
			? call_user_func( [ static::class, $method ], $value )
			: $method( $value );
	}

	private static function array( mixed $value ): bool {
		return is_array( $value );
	}

	private static function email( mixed $value ): bool {
		return filter_var( $value, FILTER_VALIDATE_EMAIL );
	}

	private static function required( mixed $value ): bool {
		if ( is_null( $value ) ) {
			return false;
		} elseif ( is_string( $value ) && trim( $value ) === '' ) {
			return false;
		} elseif ( is_countable( $value ) && count( $value ) < 1 ) {
			return false;
		}

		return true;
	}

	private static function sometimes( mixed $value ): bool {
		return true;
	}

	private static function nullable( mixed $value = null ): bool {
		return is_null( $value );
	}

	private static function phone( mixed $value ): bool {
		return preg_match( '%^(?:(?:\(?(?:00|\+)([1-4]\d\d|[1-9]\d?)\)?)?[\-\.\ \\\/]?)?((?:\(?\d{1,}\)?[\-\.\ \\\/]?){0,})(?:[\-\.\ \\\/]?(?:#|ext\.?|extension|x)[\-\.\ \\\/]?(\d+))?$%i',
				$value ) && strlen( $value ) >= 10;
	}

	private static function string( mixed $value ): bool {
		return is_string( $value );
	}

	private static function bool( mixed $value ): bool {
		$acceptable = [ true, false, 0, 1, '0', '1' ];

		return in_array( $value, $acceptable, true );
	}

	private static function date( mixed $value ): bool {
		if ( $value instanceof DateTimeInterface ) {
			return true;
		}
		try {
			if ( ( ! is_string( $value ) && ! is_numeric( $value ) ) || strtotime( $value ) === false ) {
				return false;
			}
		} catch ( Exception $e ) {
			return false;
		}

		$date = date_parse( $value );

		return checkdate( $date['month'], $date['day'], $date['year'] );
	}

	private static function int( mixed $value ): bool {
		return is_int( $value );
	}

	private static function numeric( mixed $value ): bool {
		return is_numeric( $value );
	}
}