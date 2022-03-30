<?php

namespace WPN\Plugins;

interface Plugin {
	public function __invoke();

	public function register(): self;
}