<?php

namespace WPN\Support\Plugins\Support;

use Walker;

class CustomTaxonomyListWalker extends Walker {
	var $tree_type = 'category';
	var $db_fields = array( 'id' => 'term_id', 'parent' => 'parent' );

	function start_el( &$output, $term, $depth = 0, $args = array(), $current_object_id = 0 ) {
		$term      = get_term( $term, $term->taxonomy );
		$term_slug = $term->slug;

		$text = str_repeat( '&nbsp;', $depth * 3 ) . $term->name;
		if ( $args['show_count'] ) {
			$text .= '&nbsp;(' . $term->count . ')';
		}

		$class_name = 'level-' . $depth;

		$output .= "\t" . '<option' . ' class="' . esc_attr( $class_name ) . '" value="' . esc_attr( $term_slug ) . '">' . esc_html( $text ) . '</option>' . "\n";
	}
}