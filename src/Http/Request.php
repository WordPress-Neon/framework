<?php

namespace WPN\Http;

use WPN\Validation\Validator;
use WPN\Validation\ValidatorException;

abstract class Request {
	protected Validator $validator;
	protected array $fields;

	public function __construct() {
		$this->fields = $_POST;

		$this->sanitize();

		$this->validator = new Validator();
	}

	abstract protected function rules(): array;

	protected function validator(): Validator {
		return $this->validator;
	}

	private function sanitize(): self {
		$valid = [];

		unset( $this->fields['action'] );
		unset( $this->fields['security'] );

		foreach ( $this->fields as $key => $value ) {
			if ( is_array( $value ) ) {
				$valid[ $key ] = rest_sanitize_array( $value );
				continue;
			}
			$valid[ $key ] = sanitize_text_field( $value );
		}

		$this->fields = $valid;

		return $this;
	}

	/**
	 * @throws ValidatorException
	 */
	public function validated(): array {
		$validated_fields = [];
		$invalid_fields   = [ ...$this->check_required_fields_exist() ];

		foreach ( $this->fields as $key => $value ) {
			$valid = true;

			if ( ! isset( $this->rules()[ $key ] ) ) {
				continue;
			}

			$rules = $this->rules()[ $key ];
			foreach ( $rules as $rule ) {
				if ( ! $this->validator()->validate( $this->fields[ $key ], $rule ) ) {
					$valid                    = false;
					$invalid_fields[][ $key ] = $this->get_invalid_message( $key, $rule );
				}
			}

			if ( $valid ) {
				$validated_fields[ $key ] = $this->fields[ $key ];
			}
		}
		if ( count( $invalid_fields ) > 0 ) {
			throw new ValidatorException( $invalid_fields );
		}

		return $validated_fields;
	}

	private function get_invalid_message( string $field, string $rule ): string {
		$messages = $this->messages();

		if ( array_key_exists( "$rule.$field", $messages ) ) {
			return sprintf( $messages["$rule.$field"], $field );
		}

		return sprintf( $messages[ $rule ], $field );
	}

	protected function messages(): array {
		return [
			'required' => '%s is a required field',
			'string'   => '%s should be a string',
			'int'      => '%s should be an integer',
			'email'    => '%s should be an email address',
			'phone'    => '%s should be a phone number',
			'bool'     => '%s should be a boolean value',
			'date'     => '%s should be a valid date',
			'numeric'  => '%s should be numeric'
		];
	}

	protected function check_required_fields_exist(): array {
		$required_fields_not_set = [];
		foreach ( $this->rules() as $field => $rules ) {
			if ( ! in_array( 'required', $rules ) ) {
				continue;
			}

			if ( ! array_key_exists( $field, $this->fields ) ) {
				$required_fields_not_set[ $field ][] = $this->get_invalid_message( $field, 'required' );
			}
		}

		return $required_fields_not_set;
	}
}

