<?php

namespace WPN\Plugins;

interface PluginInterface {
	public function __invoke();

	public function register(): self;
}