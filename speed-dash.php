<?php
/**
 * Plugin Name: SpeedDash
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
 * The code that runs during plugin activation.
 */
function speeddash_activate() {
	// Set default settings on activation.
	$default_settings = array(
		'speeddash_dashboard_widgets' => true,
		'speeddash_aggressive_dashboard' => false,
		'speeddash_hide_notices' => true,
		'speeddash_heartbeat_frequency' => true,
		'speeddash_dequeue_scripts' => true,
		'speeddash_disable_emoji' => true,
		'speeddash_cache_notice' => true,
		'speeddash_smartcache_enabled' => true,
		'speeddash_prefetch_enabled' => true,
		'speeddash_universal_optimization' => true,
		'speeddash_smart_detection' => true,
		'speeddash_aggressive_optimization' => true,
		'speeddash_database_optimization' => true,
		'speeddash_performance_monitoring' => false,
	);
	
	// Only set defaults if no settings exist.
	if ( ! get_option( 'speeddash_settings' ) ) {
		update_option( 'speeddash_settings', $default_settings );
	}
	
	// Flush rewrite rules.
	flush_rewrite_rules();
}

/**
 * The code that runs during plugin deactivation.
 */
function speeddash_deactivate() {
	// Flush rewrite rules.
	flush_rewrite_rules();
	
	// Clear any scheduled events.
	wp_clear_scheduled_hook( 'speeddash_prefetch_worker' );
}

// Register activation and deactivation hooks.
register_activation_hook( __FILE__, 'speeddash_activate' );
register_deactivation_hook( __FILE__, 'speeddash_deactivate' );

/**
 * Load the plugin files.
 */
function speeddash_load_plugin_files() {
	// Load all the includes.
	require_once SPEED_DASH_PATH . 'includes/class-speed-dash-loader.php';
	require_once SPEED_DASH_PATH . 'includes/class-speed-dash-i18n.php';
	require_once SPEED_DASH_PATH . 'includes/class-speed-dash-admin.php';
	require_once SPEED_DASH_PATH . 'includes/class-speed-dash-public.php';
	require_once SPEED_DASH_PATH . 'includes/class-speed-dash-emoji-disabler.php';
	require_once SPEED_DASH_PATH . 'includes/class-speed-dash-heartbeat-optimizer.php';
	require_once SPEED_DASH_PATH . 'includes/class-speed-dash-script-optimizer.php';
	require_once SPEED_DASH_PATH . 'includes/class-speed-dash-dashboard-optimizer.php';
	require_once SPEED_DASH_PATH . 'includes/class-speed-dash-universal-optimizer.php';
	require_once SPEED_DASH_PATH . 'includes/class-speed-dash-smart-detector.php';
	require_once SPEED_DASH_PATH . 'includes/class-speed-dash-aggressive-optimizer.php';
	require_once SPEED_DASH_PATH . 'includes/class-speed-dash-database-optimizer.php';
	require_once SPEED_DASH_PATH . 'includes/class-speed-dash-runtime-cache.php';
	require_once SPEED_DASH_PATH . 'includes/class-speed-dash-file-cache.php';
	require_once SPEED_DASH_PATH . 'includes/class-speed-dash-hybrid-cache.php';
	require_once SPEED_DASH_PATH . 'includes/class-speed-dash-prefetch-worker.php';
	require_once SPEED_DASH_PATH . 'includes/class-speed-dash-cache-detector.php';
	require_once SPEED_DASH_PATH . 'includes/class-speed-dash-smartcache.php';
	require_once SPEED_DASH_PATH . 'includes/class-speed-dash-performance-monitor.php';
	require_once SPEED_DASH_PATH . 'admin/class-speed-dash-settings.php';
}

/**
 * Initialize the plugin.
 */
function speeddash_init() {
	// Load plugin files.
	speeddash_load_plugin_files();
	
	// Initialize the main plugin class.
	$speed_dash = new Speed_Dash();
	$speed_dash->run();
}

// Hook into WordPress.
add_action( 'plugins_loaded', 'speeddash_init' );

/**
 * Main Speed Dash class.
 */
class Speed_Dash {

	/**
	 * The loader that's responsible for maintaining and registering all hooks.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 */
	public function __construct() {
		if ( defined( 'SPEED_DASH_VERSION' ) ) {
			$this->version = SPEED_DASH_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'speeddash';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->init_optimizers();
	}

	/**
	 * Load the required dependencies for this plugin.
	 */
	private function load_dependencies() {
		$this->loader = new Speed_Dash_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 */
	private function set_locale() {
		$plugin_i18n = new Speed_Dash_i18n();
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality.
	 */
	private function define_admin_hooks() {
		$plugin_admin = new Speed_Dash_Admin( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality.
	 */
	private function define_public_hooks() {
		$plugin_public = new Speed_Dash_Public( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
	}

	/**
	 * Initialize all optimizers.
	 */
	private function init_optimizers() {
		// Initialize settings page.
		new Speed_Dash_Settings( $this->get_plugin_name(), $this->get_version() );
		
		// Initialize emoji disabler.
		new Speed_Dash_Emoji_Disabler();
		
		// Initialize cache detector first.
		new Speed_Dash_Cache_Detector();
		
		// Initialize other optimizers.
		new Speed_Dash_Universal_Optimizer();
		new Speed_Dash_Smart_Detector();
		new Speed_Dash_Aggressive_Optimizer();
		new Speed_Dash_Database_Optimizer();
		new Speed_Dash_SmartCache();
		new Speed_Dash_Performance_Monitor();
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}