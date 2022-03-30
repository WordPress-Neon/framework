<?php

namespace WPN\Support\Plugins;

interface Plugin {
	public function __invoke();

	public function register(): self;
}