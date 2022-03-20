<?php

namespace WPN\Providers;

use WPN\App;

abstract class ServiceProvider {
	public function __construct() {
		$this->boot( app() );
	}

	abstract protected function boot( App $app );
}