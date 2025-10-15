<?php
/**
 * WP Fusion - Database Optimizer
 *
 * @package   WP Fusion
 * @copyright Copyright (c) 2024, Very Good Plugins, https://verygoodplugins.com
 * @license   GPL-3.0+
 * @since     3.37.14
 */

/**
 * Database optimizer functionality.
 *
 * @package    Speed_Dash
 * @subpackage Speed_Dash/includes
 * @author     Your Name <email@example.com>
 */
class Speed_Dash_Database_Optimizer {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$settings = get_option( 'speeddash_settings', array() );
		if ( ! empty( $settings['speeddash_database_optimization'] ) ) {
			$this->init_hooks();
		}
	}

	/**
	 * Initialize hooks.
	 */
	private function init_hooks() {
		add_action( 'wp_loaded', array( $this, 'optimize_database' ) );
		add_action( 'wp_scheduled_delete', array( $this, 'cleanup_expired_data' ) );
	}

	/**
	 * Optimize database.
	 */
	public function optimize_database() {
		// Clean up expired transients.
		$this->cleanup_expired_transients();
		
		// Clean up orphaned data.
		$this->cleanup_orphaned_data();
		
		// Optimize tables.
		$this->optimize_tables();
	}

	/**
	 * Clean up expired transients.
	 */
	private function cleanup_expired_transients() {
		global $wpdb;
		
		// Delete expired transients.
		$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout_%' AND option_value < UNIX_TIMESTAMP()" );
		$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_site_transient_timeout_%' AND option_value < UNIX_TIMESTAMP()" );
	}

	/**
	 * Clean up orphaned data.
	 */
	private function cleanup_orphaned_data() {
		global $wpdb;
		
		// Clean up orphaned post meta.
		$wpdb->query( "DELETE pm FROM {$wpdb->postmeta} pm LEFT JOIN {$wpdb->posts} p ON pm.post_id = p.ID WHERE p.ID IS NULL" );
		
		// Clean up orphaned comment meta.
		$wpdb->query( "DELETE cm FROM {$wpdb->commentmeta} cm LEFT JOIN {$wpdb->comments} c ON cm.comment_id = c.comment_ID WHERE c.comment_ID IS NULL" );
		
		// Clean up orphaned term relationships.
		$wpdb->query( "DELETE tr FROM {$wpdb->term_relationships} tr LEFT JOIN {$wpdb->posts} p ON tr.object_id = p.ID WHERE p.ID IS NULL" );
	}

	/**
	 * Optimize tables.
	 */
	private function optimize_tables() {
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

	/**
	 * Cleanup expired data.
	 */
	public function cleanup_expired_data() {
		// Clean up expired data.
		$this->cleanup_expired_transients();
		$this->cleanup_orphaned_data();
	}
}