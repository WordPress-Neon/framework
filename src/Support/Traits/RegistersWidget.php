<?php

namespace WPN\Support\Traits;

trait RegistersWidget {
	public function register( array $config = [] ): self {
		add_action( 'widgets_init', function () {
			register_widget( self::class );
		} );

		return $this;
	}
}