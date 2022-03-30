<?php

namespace WPN\Ajax;

interface Callback {
	public function __invoke(): Response;
}