<?php
/**
 * WP Fusion - Performance Monitor
 *
 * @package   WP Fusion
 * @copyright Copyright (c) 2024, Very Good Plugins, https://verygoodplugins.com
 * @license   GPL-3.0+
 * @since     3.37.14
 */

/**
 * Performance monitor functionality.
 *
 * @package    Speed_Dash
 * @subpackage Speed_Dash/includes
 * @author     Your Name <email@example.com>
 */
class Speed_Dash_Performance_Monitor {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$settings = get_option( 'speeddash_settings', array() );
		if ( ! empty( $settings['speeddash_performance_monitoring'] ) ) {
			$this->init_hooks();
		}
	}

	/**
	 * Initialize hooks.
	 */
	private function init_hooks() {
		add_action( 'init', array( $this, 'start_monitoring' ) );
		add_action( 'shutdown', array( $this, 'end_monitoring' ) );
	}

	/**
	 * Start monitoring.
	 */
	public function start_monitoring() {
		// Start monitoring.
		$this->start_time = microtime( true );
		$this->start_memory = memory_get_usage( true );
	}

	/**
	 * End monitoring.
	 */
	public function end_monitoring() {
		// End monitoring.
		$this->end_time = microtime( true );
		$this->end_memory = memory_get_usage( true );
		
		// Calculate metrics.
		$this->calculate_metrics();
		
		// Log metrics.
		$this->log_metrics();
	}

	/**
	 * Calculate metrics.
	 */
	private function calculate_metrics() {
		$this->execution_time = $this->end_time - $this->start_time;
		$this->memory_usage = $this->end_memory - $this->start_memory;
		$this->peak_memory = memory_get_peak_usage( true );
	}

	/**
	 * Log metrics.
	 */
	private function log_metrics() {
		$metrics = array(
			'execution_time' => $this->execution_time,
			'memory_usage' => $this->memory_usage,
			'peak_memory' => $this->peak_memory,
			'timestamp' => time(),
		);
		
		// Store metrics.
		$existing_metrics = get_option( 'speeddash_performance_metrics', array() );
		$existing_metrics[] = $metrics;
		
		// Keep only last 100 metrics.
		if ( count( $existing_metrics ) > 100 ) {
			$existing_metrics = array_slice( $existing_metrics, -100 );
		}
		
		update_option( 'speeddash_performance_metrics', $existing_metrics );
	}
}