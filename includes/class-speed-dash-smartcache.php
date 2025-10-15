<?php
/**
 * WP Fusion - SmartCache
 *
 * @package   WP Fusion
 * @copyright Copyright (c) 2024, Very Good Plugins, https://verygoodplugins.com
 * @license   GPL-3.0+
 * @since     3.37.14
 */

/**
 * SmartCache functionality.
 *
 * @package    Speed_Dash
 * @subpackage Speed_Dash/includes
 * @author     Your Name <email@example.com>
 */
class Speed_Dash_SmartCache {

	/**
	 * Runtime cache instance.
	 */
	private $runtime_cache;

	/**
	 * File cache instance.
	 */
	private $file_cache;

	/**
	 * Hybrid cache instance.
	 */
	private $hybrid_cache;

	/**
	 * Prefetch worker instance.
	 */
	private $prefetch_worker;

	/**
	 * Constructor.
	 */
	public function __construct() {
		// Only run if SmartCache is enabled and no better cache solution is available.
		if ( ! get_option( 'speeddash_smartcache_enabled', true ) ) {
			return;
		}

		// Check if SmartCache should be enabled (no Redis/Memcached available).
		if ( ! Speed_Dash_Cache_Detector::should_enable_smartcache() ) {
			// Log that SmartCache is disabled due to better cache solution.
			error_log( 'Speed Dash SmartCache: Disabled - Better cache solution detected (Redis/Memcached)' );
			return;
		}

		$this->init_hooks();
	}

	/**
	 * Initialize hooks.
	 */
	private function init_hooks() {
		add_action( 'init', array( $this, 'init_caches' ) );
		add_action( 'wp_ajax_speeddash_clear_cache', array( $this, 'ajax_clear_cache' ) );
		add_action( 'wp_ajax_nopriv_speeddash_clear_cache', array( $this, 'ajax_clear_cache' ) );
	}

	/**
	 * Initialize cache instances.
	 */
	public function init_caches() {
		// Initialize runtime cache.
		$this->runtime_cache = new Speed_Dash_Runtime_Cache();

		// Initialize file cache.
		$this->file_cache = new Speed_Dash_File_Cache();

		// Initialize hybrid cache.
		$this->hybrid_cache = new Speed_Dash_Hybrid_Cache( $this->runtime_cache, $this->file_cache );

		// Initialize prefetch worker if enabled.
		if ( get_option( 'speeddash_prefetch_enabled', true ) ) {
			$this->prefetch_worker = new Speed_Dash_Prefetch_Worker( $this->hybrid_cache );
		}
	}

	/**
	 * Get cache instance.
	 */
	public function get_cache() {
		return $this->hybrid_cache;
	}

	/**
	 * Clear all cache.
	 */
	public static function clear_all_cache() {
		// Clear runtime cache.
		if ( class_exists( 'Speed_Dash_Runtime_Cache' ) ) {
			Speed_Dash_Runtime_Cache::clear_all();
		}

		// Clear file cache.
		if ( class_exists( 'Speed_Dash_File_Cache' ) ) {
			Speed_Dash_File_Cache::clear_all();
		}

		// Clear WordPress transients.
		global $wpdb;
		$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_%' OR option_name LIKE '_site_transient_%'" );
	}

	/**
	 * Get cache statistics.
	 */
	public static function get_stats() {
		$stats = array(
			'hybrid_cache' => array(
				'runtime' => array(
					'hits' => 0,
					'misses' => 0,
					'hit_rate' => 0,
					'cache_size' => 0,
				),
				'file' => array(
					'hits' => 0,
					'misses' => 0,
					'hit_rate' => 0,
					'file_count' => 0,
					'total_size_mb' => 0,
				),
				'hybrid' => array(
					'total_hits' => 0,
					'misses' => 0,
					'hit_rate' => 0,
				),
			),
		);

		// Get runtime cache stats.
		if ( class_exists( 'Speed_Dash_Runtime_Cache' ) ) {
			$runtime_stats = Speed_Dash_Runtime_Cache::get_stats();
			$stats['hybrid_cache']['runtime'] = $runtime_stats;
		}

		// Get file cache stats.
		if ( class_exists( 'Speed_Dash_File_Cache' ) ) {
			$file_stats = Speed_Dash_File_Cache::get_stats();
			$stats['hybrid_cache']['file'] = $file_stats;
		}

		// Calculate hybrid stats.
		$stats['hybrid_cache']['hybrid']['total_hits'] = $stats['hybrid_cache']['runtime']['hits'] + $stats['hybrid_cache']['file']['hits'];
		$stats['hybrid_cache']['hybrid']['misses'] = $stats['hybrid_cache']['runtime']['misses'] + $stats['hybrid_cache']['file']['misses'];
		
		$total_requests = $stats['hybrid_cache']['hybrid']['total_hits'] + $stats['hybrid_cache']['hybrid']['misses'];
		if ( $total_requests > 0 ) {
			$stats['hybrid_cache']['hybrid']['hit_rate'] = round( ( $stats['hybrid_cache']['hybrid']['total_hits'] / $total_requests ) * 100, 2 );
		}

		return $stats;
	}

	/**
	 * Get performance metrics.
	 */
	public static function get_performance_metrics() {
		$stats = self::get_stats();
		
		$cache_efficiency = $stats['hybrid_cache']['hybrid']['hit_rate'];
		$estimated_time_saved_ms = $stats['hybrid_cache']['hybrid']['total_hits'] * 50; // Assume 50ms saved per hit.
		$estimated_time_saved_percentage = min( 100, $cache_efficiency * 0.8 ); // Assume 80% of hit rate as time saved.

		return array(
			'cache_efficiency' => $cache_efficiency,
			'estimated_time_saved_ms' => $estimated_time_saved_ms,
			'estimated_time_saved_percentage' => $estimated_time_saved_percentage . '%',
		);
	}

	/**
	 * Get cache health.
	 */
	public static function get_cache_health() {
		$stats = self::get_stats();
		$hit_rate = $stats['hybrid_cache']['hybrid']['hit_rate'];
		
		$health = array(
			'status' => 'good',
			'score' => 0,
			'issues' => array(),
			'recommendations' => array(),
		);

		// Calculate health score.
		if ( $hit_rate >= 80 ) {
			$health['status'] = 'excellent';
			$health['score'] = 100;
		} elseif ( $hit_rate >= 60 ) {
			$health['status'] = 'good';
			$health['score'] = 80;
		} elseif ( $hit_rate >= 40 ) {
			$health['status'] = 'fair';
			$health['score'] = 60;
		} else {
			$health['status'] = 'poor';
			$health['score'] = 40;
		}

		// Add issues and recommendations.
		if ( $hit_rate < 60 ) {
			$health['issues'][] = 'Low cache hit rate (' . $hit_rate . '%)';
			$health['recommendations'][] = 'Consider enabling prefetch worker';
		}

		if ( $stats['hybrid_cache']['file']['file_count'] > 1000 ) {
			$health['issues'][] = 'Large number of cache files (' . $stats['hybrid_cache']['file']['file_count'] . ')';
			$health['recommendations'][] = 'Consider clearing old cache files';
		}

		return $health;
	}

	/**
	 * AJAX clear cache.
	 */
	public function ajax_clear_cache() {
		// Check nonce.
		if ( ! wp_verify_nonce( $_POST['nonce'], 'speeddash_clear_cache' ) ) {
			wp_die( 'Security check failed.' );
		}

		// Check user capabilities.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'You do not have sufficient permissions to perform this action.' );
		}

		// Clear cache.
		self::clear_all_cache();

		// Return success response.
		wp_send_json_success( array( 'message' => 'Cache cleared successfully.' ) );
	}
}