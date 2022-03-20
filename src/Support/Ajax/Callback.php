<?php

namespace WPN\Support\Ajax;

interface Callback {
	public function __invoke(): Response;
}