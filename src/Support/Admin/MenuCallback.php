<?php

namespace App\Support\Admin;

interface MenuCallback {
	public function __invoke(): void;
}