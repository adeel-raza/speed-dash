<?php
/**
 * WP Fusion - Script Optimizer
 *
 * @package   WP Fusion
 * @copyright Copyright (c) 2024, Very Good Plugins, https://verygoodplugins.com
 * @license   GPL-3.0+
 * @since     3.37.14
 */

/**
 * Script optimizer functionality.
 *
 * @package    Speed_Dash
 * @subpackage Speed_Dash/includes
 * @author     Your Name <email@example.com>
 */
class Speed_Dash_Script_Optimizer {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$settings = get_option( 'speeddash_settings', array() );
		if ( ! empty( $settings['speeddash_dequeue_scripts'] ) ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'dequeue_scripts' ) );
		}
	}

	/**
	 * Dequeue unnecessary scripts.
	 */
	public function dequeue_scripts() {
		wp_dequeue_script( 'jquery-ui-core' );
		wp_dequeue_script( 'jquery-ui-widget' );
		wp_dequeue_script( 'jquery-ui-mouse' );
		wp_dequeue_script( 'jquery-ui-sortable' );
		wp_dequeue_script( 'jquery-ui-draggable' );
		wp_dequeue_script( 'jquery-ui-droppable' );
		wp_dequeue_script( 'jquery-ui-selectable' );
		wp_dequeue_script( 'jquery-ui-position' );
		wp_dequeue_script( 'jquery-ui-menu' );
		wp_dequeue_script( 'jquery-ui-autocomplete' );
		wp_dequeue_script( 'jquery-ui-tooltip' );
		wp_dequeue_script( 'jquery-ui-tabs' );
		wp_dequeue_script( 'jquery-ui-slider' );
		wp_dequeue_script( 'jquery-ui-progressbar' );
		wp_dequeue_script( 'jquery-ui-dialog' );
		wp_dequeue_script( 'jquery-ui-button' );
		wp_dequeue_script( 'jquery-ui-datepicker' );
		wp_dequeue_script( 'jquery-ui-accordion' );
		wp_dequeue_script( 'jquery-ui-resizable' );
		wp_dequeue_script( 'jquery-ui-selectmenu' );
		wp_dequeue_script( 'jquery-ui-spinner' );
		wp_dequeue_script( 'jquery-ui-tooltip' );
	}
}