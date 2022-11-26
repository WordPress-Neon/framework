<?php

namespace WPN\Validation;

use Exception;

class ValidatorException extends Exception {
	public function __construct( array $failures ) {
		$status = 400;
		
		die( json_encode( compact( 'status', 'failures' ) ) );
	}
}