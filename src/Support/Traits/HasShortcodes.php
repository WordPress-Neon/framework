<?php

namespace WPN\Support\Traits;

trait HasShortcodes {
	public static function shortcodes(): array {
		return apply_filters( 'app_shortcodes', [] );
	}
}