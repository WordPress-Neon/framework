<?php

namespace WPN\Support;

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
			$valid[ $key ] = sanitize_text_field( $value );
		}

		$this->fields = $valid;

		return $this;
	}

	public function validated(): array {
		$validated_fields = [];
		foreach ( $this->fields as $key => $value ) {
			$valid = true;

			if ( ! isset( $this->rules()[ $key ] ) ) {
				continue;
			}

			$rules = $this->rules()[ $key ];
			foreach ( $rules as $rule ) {
				if ( ! $this->validator()->validate( $this->fields[ $key ], $rule ) ) {
					$valid = false;
				}
			}

			if ( $valid ) {
				$validated_fields[ $key ] = $this->fields[ $key ];
			}
		}

		return $validated_fields;
	}
}

