<?php

namespace WPN\Shortcodes;

trait RegistersShortcode {
	public string $handle;

	private function registerShortcode( string $handle ): void {
		$this->handle = $handle;

		add_filter( 'app_shortcodes', fn( $shortcodes ) => [ ...$shortcodes, $this ], 10, 1 );
	}

	public function __toString(): string {
		return $this->handle;
	}
}