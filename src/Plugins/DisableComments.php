<?php

namespace WPN\Plugins;

use WPN\Support\Traits\RegistersPlugin;

class DisableComments implements PluginInterface {
	use RegistersPlugin;

	public function __invoke() {
		add_action( 'widgets_init', array( $this, 'disableRcWidget' ) );
		add_filter( 'wp_headers', array( $this, 'filterWpHeaders' ) );
		add_action( 'template_redirect', array( $this, 'filterQuery' ), 9 );
		add_action( 'template_redirect', array( $this, 'filterAdminBar' ) );
		add_action( 'admin_init', array( $this, 'filterAdminBar' ) );
		add_filter( 'rest_endpoints', array( $this, 'filterRESTEndpoints' ) );
		add_action( 'admin_menu', array( $this, 'filterAdminMenu' ), 9999 );
		add_action( 'wp_dashboard_setup', array( $this, 'filterDashboard' ) );
		add_filter( 'feed_links_show_comments_feed', '__return_false' );
	}

	public function filterDashboard(): void {
		remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
	}

	public function filterAdminMenu(): void {
		global $pagenow;
		if ( $pagenow == 'comment.php' || $pagenow == 'edit-comments.php' ) {
			wp_die( 'Comments are closed.', '', array( 'response' => 403 ) );
		}
		remove_menu_page( 'edit-comments.php' );
		if ( $pagenow == 'options-discussion.php' ) {
			wp_die( 'Comments are closed.', '', array( 'response' => 403 ) );
		}
		remove_submenu_page( 'options-general.php', 'options-discussion.php' );
	}

	public function filterRESTEndpoints( array $endpoints ): array {
		unset( $endpoints['comments'] );

		return $endpoints;
	}

	public function filterAdminBar(): void {
		if ( is_admin_bar_showing() ) {
			remove_action( 'admin_bar_menu', 'wp_admin_bar_comments_menu', 60 );
		}
	}

	public function filterWpHeaders( array $headers ): array {
		unset( $headers['X-Pingback'] );

		return $headers;
	}

	public function disableRcWidget(): void {
		unregister_widget( 'WP_Widget_Recent_Comments' );
		/**
		 * The widget has added a style action when it was constructed - which will
		 * still fire even if we now unregister the widget... so filter that out
		 */
		add_filter( 'show_recent_comments_widget_style', '__return_false' );
	}

	public function filterQuery(): void {
		if ( is_comment_feed() ) {
			wp_die( 'Comments are closed.', '', array( 'response' => 403 ) );
		}
	}
}