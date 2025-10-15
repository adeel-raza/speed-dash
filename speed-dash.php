<?php
/**
 * Plugin Name: Speed Dash
 * Plugin URI: https://github.com/adeel-raza/speed-dash
 * Description: Diagnose and fix WordPress admin backend slowness with one-click safe optimizations. Backend-only, non-invasive.
 * Version: 1.0.0
 * Author: Adeel Raza
 * Author URI: https://github.com/adeel-raza
 * License: GPL-3.0+
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: speeddash
 * Domain Path: /languages
 *
 * @package SpeedDash
 * @since 1.0.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'SPEED_DASH_VERSION', '1.0.0' );
define( 'SPEED_DASH_PATH', plugin_dir_path( __FILE__ ) );
define( 'SPEED_DASH_URL', plugin_dir_url( __FILE__ ) );

/**
 * Speed Dash main class.
 */
class Speed_Dash {

	/**
	 * Constructor.
	 */
	public function __construct() {
		// Only initialize in admin area.
		if ( is_admin() ) {
			add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
			add_action( 'admin_init', array( $this, 'register_settings' ) );
			add_action( 'admin_init', array( $this, 'init_optimizations' ) );
		}
	}

	/**
	 * Add admin menu.
	 */
	public function add_admin_menu() {
		add_options_page(
			__( 'Speed Dash Settings', 'speeddash' ),
			__( 'Speed Dash', 'speeddash' ),
			'manage_options',
			'speed-dash',
			array( $this, 'admin_page' )
		);
	}

	/**
	 * Register settings.
	 */
	public function register_settings() {
		register_setting( 'speeddash_settings', 'speeddash_settings' );
	}

	/**
	 * Initialize optimizations.
	 */
	public function init_optimizations() {
		$settings = get_option( 'speeddash_settings', $this->get_default_settings() );

		// Dashboard widgets.
		if ( $settings['dashboard_widgets'] ) {
			add_action( 'wp_dashboard_setup', array( $this, 'remove_dashboard_widgets' ) );
		}

		// Hide admin notices.
		if ( $settings['hide_notices'] ) {
			add_action( 'admin_head', array( $this, 'hide_admin_notices' ) );
		}

		// Heartbeat frequency.
		if ( $settings['heartbeat_frequency'] ) {
			add_action( 'init', array( $this, 'optimize_heartbeat' ) );
		}

		// Script optimization.
		if ( $settings['dequeue_scripts'] ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'dequeue_scripts' ) );
		}

		// Disable emoji.
		if ( $settings['disable_emoji'] ) {
			add_action( 'init', array( $this, 'disable_emoji' ) );
		}
	}

	/**
	 * Get default settings.
	 */
	private function get_default_settings() {
		return array(
			'dashboard_widgets' => true,
			'hide_notices' => true,
			'heartbeat_frequency' => true,
			'dequeue_scripts' => true,
			'disable_emoji' => true,
		);
	}

	/**
	 * Remove dashboard widgets.
	 */
	public function remove_dashboard_widgets() {
		remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
		remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );
		remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
		remove_meta_box( 'dashboard_secondary', 'dashboard', 'side' );
		remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
		remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
		remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
		remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
		remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' );
	}

	/**
	 * Hide admin notices.
	 */
	public function hide_admin_notices() {
		echo '<style>.notice, .error, .updated { display: none !important; }</style>';
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

	/**
	 * Disable emoji.
	 */
	public function disable_emoji() {
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	}

	/**
	 * Admin page.
	 */
	public function admin_page() {
		$settings = get_option( 'speeddash_settings', $this->get_default_settings() );
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Speed Dash Settings', 'speeddash' ); ?></h1>
			
			<?php if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] === 'true' ) : ?>
				<div class="notice notice-success is-dismissible">
					<p><?php esc_html_e( 'Settings saved successfully!', 'speeddash' ); ?></p>
				</div>
			<?php endif; ?>

			<form method="post" action="options.php">
				<?php settings_fields( 'speeddash_settings' ); ?>
				
				<table class="form-table">
					<tr>
						<th scope="row"><?php esc_html_e( 'Dashboard Widgets', 'speeddash' ); ?></th>
						<td>
							<label>
								<input type="checkbox" name="speeddash_settings[dashboard_widgets]" value="1" <?php checked( $settings['dashboard_widgets'] ); ?>>
								<?php esc_html_e( 'Remove unnecessary dashboard widgets', 'speeddash' ); ?>
							</label>
						</td>
					</tr>
					
					<tr>
						<th scope="row"><?php esc_html_e( 'Admin Notices', 'speeddash' ); ?></th>
						<td>
							<label>
								<input type="checkbox" name="speeddash_settings[hide_notices]" value="1" <?php checked( $settings['hide_notices'] ); ?>>
								<?php esc_html_e( 'Hide admin notices and warnings', 'speeddash' ); ?>
							</label>
						</td>
					</tr>
					
					<tr>
						<th scope="row"><?php esc_html_e( 'Heartbeat Frequency', 'speeddash' ); ?></th>
						<td>
							<label>
								<input type="checkbox" name="speeddash_settings[heartbeat_frequency]" value="1" <?php checked( $settings['heartbeat_frequency'] ); ?>>
								<?php esc_html_e( 'Reduce heartbeat frequency from 15s to 60s', 'speeddash' ); ?>
							</label>
						</td>
					</tr>
					
					<tr>
						<th scope="row"><?php esc_html_e( 'Script Optimization', 'speeddash' ); ?></th>
						<td>
							<label>
								<input type="checkbox" name="speeddash_settings[dequeue_scripts]" value="1" <?php checked( $settings['dequeue_scripts'] ); ?>>
								<?php esc_html_e( 'Remove unnecessary jQuery UI scripts', 'speeddash' ); ?>
							</label>
						</td>
					</tr>
					
					<tr>
						<th scope="row"><?php esc_html_e( 'Emoji', 'speeddash' ); ?></th>
						<td>
							<label>
								<input type="checkbox" name="speeddash_settings[disable_emoji]" value="1" <?php checked( $settings['disable_emoji'] ); ?>>
								<?php esc_html_e( 'Disable WordPress emoji scripts and styles', 'speeddash' ); ?>
							</label>
						</td>
					</tr>
				</table>
				
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}
}

// Initialize the plugin safely.
function speeddash_init() {
	if ( is_admin() ) {
		new Speed_Dash();
	}
}

// Hook into WordPress.
add_action( 'plugins_loaded', 'speeddash_init' );