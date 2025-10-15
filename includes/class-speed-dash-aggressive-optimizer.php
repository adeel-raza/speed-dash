<?php
/**
 * WP Fusion - Aggressive Optimizer
 *
 * @package   WP Fusion
 * @copyright Copyright (c) 2024, Very Good Plugins, https://verygoodplugins.com
 * @license   GPL-3.0+
 * @since     3.37.14
 */

/**
 * Aggressive optimizer functionality.
 *
 * @package    Speed_Dash
 * @subpackage Speed_Dash/includes
 * @author     Your Name <email@example.com>
 */
class Speed_Dash_Aggressive_Optimizer {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$settings = get_option( 'speeddash_settings', array() );
		if ( ! empty( $settings['speeddash_aggressive_optimization'] ) ) {
			$this->init_hooks();
		}
	}

	/**
	 * Initialize hooks.
	 */
	private function init_hooks() {
		add_action( 'muplugins_loaded', array( $this, 'early_optimizations' ), 1 );
		add_action( 'init', array( $this, 'aggressive_optimizations' ), 1 );
	}

	/**
	 * Early optimizations.
	 */
	public function early_optimizations() {
		// Disable unnecessary WordPress features.
		remove_action( 'wp_head', 'wp_generator' );
		remove_action( 'wp_head', 'wlwmanifest_link' );
		remove_action( 'wp_head', 'rsd_link' );
		remove_action( 'wp_head', 'wp_shortlink_wp_head' );
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );
		remove_action( 'wp_head', 'feed_links_extra', 3 );
		remove_action( 'wp_head', 'feed_links', 2 );
		remove_action( 'wp_head', 'index_rel_link' );
		remove_action( 'wp_head', 'parent_post_rel_link' );
		remove_action( 'wp_head', 'start_post_rel_link' );
		remove_action( 'wp_head', 'wp_resource_hints', 2 );
		remove_action( 'wp_head', 'rest_output_link_wp_head' );
		remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
		remove_action( 'wp_head', 'wp_oembed_add_host_js' );
		remove_action( 'wp_head', 'wp_custom_css_cb' );
		remove_action( 'wp_head', 'wp_custom_css_cb', 101 );
		remove_action( 'wp_head', 'wp_custom_css_cb', 201 );
		remove_action( 'wp_head', 'wp_custom_css_cb', 301 );
		remove_action( 'wp_head', 'wp_custom_css_cb', 401 );
		remove_action( 'wp_head', 'wp_custom_css_cb', 501 );
		remove_action( 'wp_head', 'wp_custom_css_cb', 601 );
		remove_action( 'wp_head', 'wp_custom_css_cb', 701 );
		remove_action( 'wp_head', 'wp_custom_css_cb', 801 );
		remove_action( 'wp_head', 'wp_custom_css_cb', 901 );
		remove_action( 'wp_head', 'wp_custom_css_cb', 1001 );
	}

	/**
	 * Aggressive optimizations.
	 */
	public function aggressive_optimizations() {
		// Disable WordPress features.
		$this->disable_wordpress_features();
		
		// Optimize database.
		$this->optimize_database();
		
		// Optimize memory.
		$this->optimize_memory();
	}

	/**
	 * Disable WordPress features.
	 */
	private function disable_wordpress_features() {
		// Disable XML-RPC.
		add_filter( 'xmlrpc_enabled', '__return_false' );
		
		// Disable REST API.
		add_filter( 'rest_enabled', '__return_false' );
		add_filter( 'rest_jsonp_enabled', '__return_false' );
		
		// Disable oEmbed.
		remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
		remove_action( 'wp_head', 'wp_oembed_add_host_js' );
		
		// Disable comments.
		add_filter( 'comments_open', '__return_false' );
		add_filter( 'pings_open', '__return_false' );
		
		// Disable trackbacks.
		add_filter( 'trackbacks_open', '__return_false' );
	}

	/**
	 * Optimize database.
	 */
	private function optimize_database() {
		// Optimize database tables.
		add_action( 'wp_loaded', array( $this, 'optimize_tables' ) );
	}

	/**
	 * Optimize memory.
	 */
	private function optimize_memory() {
		// Optimize memory usage.
		if ( function_exists( 'gc_collect_cycles' ) ) {
			gc_collect_cycles();
		}
	}

	/**
	 * Optimize tables.
	 */
	public function optimize_tables() {
		global $wpdb;
		
		// Optimize WordPress tables.
		$tables = array(
			$wpdb->posts,
			$wpdb->postmeta,
			$wpdb->comments,
			$wpdb->commentmeta,
			$wpdb->terms,
			$wpdb->term_taxonomy,
			$wpdb->term_relationships,
			$wpdb->termmeta,
			$wpdb->users,
			$wpdb->usermeta,
			$wpdb->options,
		);
		
		foreach ( $tables as $table ) {
			$wpdb->query( "OPTIMIZE TABLE {$table}" );
		}
	}
}