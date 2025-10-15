<?php
/**
 * WP Fusion - Heartbeat Optimizer
 *
 * @package   WP Fusion
 * @copyright Copyright (c) 2024, Very Good Plugins, https://verygoodplugins.com
 * @license   GPL-3.0+
 * @since     3.37.14
 */

/**
 * Heartbeat optimizer functionality.
 *
 * @package    Speed_Dash
 * @subpackage Speed_Dash/includes
 * @author     Your Name <email@example.com>
 */
class Speed_Dash_Heartbeat_Optimizer {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$settings = get_option( 'speeddash_settings', array() );
		if ( ! empty( $settings['speeddash_heartbeat_frequency'] ) ) {
			add_action( 'init', array( $this, 'optimize_heartbeat' ) );
		}
	}

	/**
	 * Optimize heartbeat frequency.
	 */
	public function optimize_heartbeat() {
		wp_deregister_script( 'heartbeat' );
		wp_register_script( 'heartbeat', admin_url( 'js/heartbeat.min.js' ), array( 'jquery' ), '1.6.3', true );
		wp_localize_script( 'heartbeat', 'heartbeatSettings', array(
			'interval' => 60, // 60 seconds instead of 15
		) );
	}
}