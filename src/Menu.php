<?php

namespace WPN;

class Menu {
	protected array $raw_menu_items = [];
	protected array $menu_items = [];

	public function __construct( protected string $menu_id ) {
		$this->raw_menu_items = wp_get_nav_menu_items( $this->menu_id );

		foreach ( $this->raw_menu_items as $item ) {
			if ( ! empty( $item->menu_item_parent ) ) {
				continue;
			}
			$this->menu_items[ $item->ID ]             = array();
			$this->menu_items[ $item->ID ]['ID']       = $item->ID;
			$this->menu_items[ $item->ID ]['title']    = $item->title;
			$this->menu_items[ $item->ID ]['url']      = $item->url;
			$this->menu_items[ $item->ID ]['children'] = array();
		}

		$submenus = array();
		foreach ( $this->raw_menu_items as $item ) {
			if ( ! $item->menu_item_parent ) {
				continue;
			}
			$submenus[ $item->ID ]                                                = array();
			$submenus[ $item->ID ]['ID']                                          = $item->ID;
			$submenus[ $item->ID ]['title']                                       = $item->title;
			$submenus[ $item->ID ]['url']                                         = $item->url;
			$this->menu_items[ $item->menu_item_parent ]['children'][ $item->ID ] = $submenus[ $item->ID ];
		}
	}

	public function as_array(): array {
		return $this->menu_items;
	}

	public function as_object(): object {
		return (object) $this->menu_items;
	}

	public function as_json(): string {
		return json_encode( $this->menu_items );
	}
}