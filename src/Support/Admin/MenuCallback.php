<?php

namespace WPN\Support\Admin;

interface MenuCallback {
	public function __invoke(): void;
}