<?php

namespace WPN\Support\Traits;

trait RegistersPlugin {
	protected object $config;

	public function register( array $config = [] ): self {
		$this->config = (object) $config;
		$this();

		return $this;
	}
}