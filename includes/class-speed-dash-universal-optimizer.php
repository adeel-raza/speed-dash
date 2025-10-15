<?php
/**
 * WP Fusion - Universal Optimizer
 *
 * @package   WP Fusion
 * @copyright Copyright (c) 2024, Very Good Plugins, https://verygoodplugins.com
 * @license   GPL-3.0+
 * @since     3.37.14
 */

/**
 * Universal optimizer functionality.
 *
 * @package    Speed_Dash
 * @subpackage Speed_Dash/includes
 * @author     Your Name <email@example.com>
 */
class Speed_Dash_Universal_Optimizer {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$settings = get_option( 'speeddash_settings', array() );
		if ( ! empty( $settings['speeddash_universal_optimization'] ) ) {
			$this->init_hooks();
		}
	}

	/**
	 * Initialize hooks.
	 */
	private function init_hooks() {
		add_action( 'admin_init', array( $this, 'optimize_admin_scripts' ), 1 );
		add_action( 'admin_init', array( $this, 'optimize_admin_styles' ), 1 );
		add_action( 'admin_init', array( $this, 'optimize_database_queries' ), 1 );
	}

	/**
	 * Optimize admin scripts.
	 */
	public function optimize_admin_scripts() {
		// Remove unnecessary admin scripts.
		wp_dequeue_script( 'thickbox' );
		wp_dequeue_script( 'media-upload' );
		wp_dequeue_script( 'jquery-ui-dialog' );
		wp_dequeue_script( 'jquery-ui-tabs' );
		wp_dequeue_script( 'jquery-ui-accordion' );
		wp_dequeue_script( 'jquery-ui-datepicker' );
		wp_dequeue_script( 'jquery-ui-slider' );
		wp_dequeue_script( 'jquery-ui-progressbar' );
		wp_dequeue_script( 'jquery-ui-tooltip' );
		wp_dequeue_script( 'jquery-ui-spinner' );
		wp_dequeue_script( 'jquery-ui-selectmenu' );
		wp_dequeue_script( 'jquery-ui-resizable' );
		wp_dequeue_script( 'jquery-ui-draggable' );
		wp_dequeue_script( 'jquery-ui-droppable' );
		wp_dequeue_script( 'jquery-ui-sortable' );
		wp_dequeue_script( 'jquery-ui-selectable' );
		wp_dequeue_script( 'jquery-ui-position' );
		wp_dequeue_script( 'jquery-ui-menu' );
		wp_dequeue_script( 'jquery-ui-autocomplete' );
		wp_dequeue_script( 'jquery-ui-button' );
		wp_dequeue_script( 'jquery-ui-widget' );
		wp_dequeue_script( 'jquery-ui-mouse' );
		wp_dequeue_script( 'jquery-ui-core' );
	}

	/**
	 * Optimize admin styles.
	 */
	public function optimize_admin_styles() {
		// Remove unnecessary admin styles.
		wp_dequeue_style( 'thickbox' );
		wp_dequeue_style( 'media-upload' );
		wp_dequeue_style( 'jquery-ui-dialog' );
		wp_dequeue_style( 'jquery-ui-tabs' );
		wp_dequeue_style( 'jquery-ui-accordion' );
		wp_dequeue_style( 'jquery-ui-datepicker' );
		wp_dequeue_style( 'jquery-ui-slider' );
		wp_dequeue_style( 'jquery-ui-progressbar' );
		wp_dequeue_style( 'jquery-ui-tooltip' );
		wp_dequeue_style( 'jquery-ui-spinner' );
		wp_dequeue_style( 'jquery-ui-selectmenu' );
		wp_dequeue_style( 'jquery-ui-resizable' );
		wp_dequeue_style( 'jquery-ui-draggable' );
		wp_dequeue_style( 'jquery-ui-droppable' );
		wp_dequeue_style( 'jquery-ui-sortable' );
		wp_dequeue_style( 'jquery-ui-selectable' );
		wp_dequeue_style( 'jquery-ui-position' );
		wp_dequeue_style( 'jquery-ui-menu' );
		wp_dequeue_style( 'jquery-ui-autocomplete' );
		wp_dequeue_style( 'jquery-ui-button' );
		wp_dequeue_style( 'jquery-ui-widget' );
		wp_dequeue_style( 'jquery-ui-mouse' );
		wp_dequeue_style( 'jquery-ui-core' );
	}

	/**
	 * Optimize database queries.
	 */
	public function optimize_database_queries() {
		// Optimize database queries.
		add_filter( 'posts_where', array( $this, 'optimize_posts_where' ) );
		add_filter( 'posts_orderby', array( $this, 'optimize_posts_orderby' ) );
		add_filter( 'posts_groupby', array( $this, 'optimize_posts_groupby' ) );
	}

	/**
	 * Optimize posts where clause.
	 */
	public function optimize_posts_where( $where ) {
		// Remove unnecessary WHERE clauses.
		$where = preg_replace( '/\s+AND\s+\([^)]*\)\s*$/', '', $where );
		return $where;
	}

	/**
	 * Optimize posts orderby clause.
	 */
	public function optimize_posts_orderby( $orderby ) {
		// Simplify ORDER BY clauses.
		$orderby = preg_replace( '/\s+ASC\s*$/', '', $orderby );
		$orderby = preg_replace( '/\s+DESC\s*$/', ' DESC', $orderby );
		return $orderby;
	}

	/**
	 * Optimize posts groupby clause.
	 */
	public function optimize_posts_groupby( $groupby ) {
		// Simplify GROUP BY clauses.
		$groupby = preg_replace( '/\s+,\s*/', ', ', $groupby );
		return $groupby;
	}
}