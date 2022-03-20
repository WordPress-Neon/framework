<?php

namespace WPN\Providers;

abstract class ServiceProvider {
	public function __construct() {
		$this->boot();
	}

	abstract protected function boot();
}