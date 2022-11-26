<?php

namespace WPN\Http;

interface Callback {
	public function __invoke(): Response;
}