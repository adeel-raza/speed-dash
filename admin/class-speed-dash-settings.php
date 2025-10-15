<?php
/**
 * WP Fusion - Settings
 *
 * @package   WP Fusion
 * @copyright Copyright (c) 2024, Very Good Plugins, https://verygoodplugins.com
 * @license   GPL-3.0+
 * @since     3.37.14
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    Speed_Dash
 * @subpackage Speed_Dash/admin
 * @author     Your Name <email@example.com>
 */
class Speed_Dash_Settings {

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_post_speeddash_clear_cache', array( $this, 'clear_cache' ) );
		add_action( 'admin_post_speeddash_refresh_detection', array( $this, 'refresh_detection' ) );
	}

	/**
	 * Add admin menu.
	 */
	public function add_admin_menu() {
		add_dashboard_page(
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
	 * Admin page.
	 */
	public function admin_page() {
		$settings = get_option( 'speeddash_settings', $this->get_default_settings() );
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Speed Dash Settings', 'speeddash' ); ?></h1>
			
			<?php
			// Display success messages.
			if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] === 'true' ) {
				echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Settings saved successfully!', 'speeddash' ) . '</p></div>';
			}
			if ( isset( $_GET['restored'] ) && $_GET['restored'] === 'true' ) {
				echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Settings restored to defaults!', 'speeddash' ) . '</p></div>';
			}
			if ( isset( $_GET['cache-cleared'] ) && $_GET['cache-cleared'] === 'true' ) {
				echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'All cache cleared successfully!', 'speeddash' ) . '</p></div>';
			}
			if ( isset( $_GET['detection-refreshed'] ) && $_GET['detection-refreshed'] === 'true' ) {
				echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Cache detection refreshed successfully!', 'speeddash' ) . '</p></div>';
			}
			?>

			<div class="speeddash-admin-wrapper">
				<div class="speeddash-main-content">
					<form method="post" action="options.php">
						<?php settings_fields( 'speeddash_settings' ); ?>
						
						<div class="speeddash-sections">
							<!-- Basic Optimizations -->
							<div class="speeddash-section">
								<h2><?php esc_html_e( 'Basic Optimizations', 'speeddash' ); ?></h2>
								<table class="form-table">
									<tr>
										<th scope="row"><?php esc_html_e( 'Dashboard Widgets', 'speeddash' ); ?></th>
										<td>
											<label>
												<input type="checkbox" name="speeddash_settings[speeddash_dashboard_widgets]" value="1" <?php checked( $settings['speeddash_dashboard_widgets'] ); ?>>
												<?php esc_html_e( 'Remove unnecessary dashboard widgets', 'speeddash' ); ?>
											</label>
											<p class="description"><?php esc_html_e( 'Removes widgets that slow down the dashboard loading.', 'speeddash' ); ?></p>
										</td>
									</tr>
									
									<tr>
										<th scope="row"><?php esc_html_e( 'Aggressive Dashboard', 'speeddash' ); ?></th>
										<td>
											<label>
												<input type="checkbox" name="speeddash_settings[speeddash_aggressive_dashboard]" value="1" <?php checked( $settings['speeddash_aggressive_dashboard'] ); ?>>
												<?php esc_html_e( 'Enable aggressive dashboard optimization', 'speeddash' ); ?>
											</label>
											<p class="description"><?php esc_html_e( 'More aggressive optimizations for dashboard performance.', 'speeddash' ); ?></p>
										</td>
									</tr>
									
									<tr>
										<th scope="row"><?php esc_html_e( 'Hide Admin Notices', 'speeddash' ); ?></th>
										<td>
											<label>
												<input type="checkbox" name="speeddash_settings[speeddash_hide_notices]" value="1" <?php checked( $settings['speeddash_hide_notices'] ); ?>>
												<?php esc_html_e( 'Hide admin notices and warnings', 'speeddash' ); ?>
											</label>
											<p class="description"><?php esc_html_e( 'Hides distracting admin notices for a cleaner interface.', 'speeddash' ); ?></p>
										</td>
									</tr>
									
									<tr>
										<th scope="row"><?php esc_html_e( 'Heartbeat Frequency', 'speeddash' ); ?></th>
										<td>
											<label>
												<input type="checkbox" name="speeddash_settings[speeddash_heartbeat_frequency]" value="1" <?php checked( $settings['speeddash_heartbeat_frequency'] ); ?>>
												<?php esc_html_e( 'Reduce heartbeat frequency from 15s to 60s', 'speeddash' ); ?>
											</label>
											<p class="description"><?php esc_html_e( 'Reduces server load by decreasing heartbeat frequency.', 'speeddash' ); ?></p>
										</td>
									</tr>
									
									<tr>
										<th scope="row"><?php esc_html_e( 'Script Optimization', 'speeddash' ); ?></th>
										<td>
											<label>
												<input type="checkbox" name="speeddash_settings[speeddash_dequeue_scripts]" value="1" <?php checked( $settings['speeddash_dequeue_scripts'] ); ?>>
												<?php esc_html_e( 'Remove unnecessary scripts and styles', 'speeddash' ); ?>
											</label>
											<p class="description"><?php esc_html_e( 'Removes unnecessary jQuery UI and other scripts.', 'speeddash' ); ?></p>
										</td>
									</tr>
									
									<tr>
										<th scope="row"><?php esc_html_e( 'Disable Emoji', 'speeddash' ); ?></th>
										<td>
											<label>
												<input type="checkbox" name="speeddash_settings[speeddash_disable_emoji]" value="1" <?php checked( $settings['speeddash_disable_emoji'] ); ?>>
												<?php esc_html_e( 'Disable WordPress emoji scripts and styles', 'speeddash' ); ?>
											</label>
											<p class="description"><?php esc_html_e( 'Removes emoji-related scripts and styles for better performance.', 'speeddash' ); ?></p>
										</td>
									</tr>
								</table>
							</div>

							<!-- Advanced Optimizations -->
							<div class="speeddash-section">
								<h2><?php esc_html_e( 'Advanced Optimizations', 'speeddash' ); ?></h2>
								<table class="form-table">
									<tr>
										<th scope="row"><?php esc_html_e( 'Universal Optimization', 'speeddash' ); ?></th>
										<td>
											<label>
												<input type="checkbox" name="speeddash_settings[speeddash_universal_optimization]" value="1" <?php checked( $settings['speeddash_universal_optimization'] ); ?>>
												<?php esc_html_e( 'Enable universal optimization patterns', 'speeddash' ); ?>
											</label>
											<p class="description"><?php esc_html_e( 'Applies pattern-based optimizations for any theme/plugin combination.', 'speeddash' ); ?></p>
										</td>
									</tr>
									
									<tr>
										<th scope="row"><?php esc_html_e( 'Smart Detection', 'speeddash' ); ?></th>
										<td>
											<label>
												<input type="checkbox" name="speeddash_settings[speeddash_smart_detection]" value="1" <?php checked( $settings['speeddash_smart_detection'] ); ?>>
												<?php esc_html_e( 'Enable smart performance detection', 'speeddash' ); ?>
											</label>
											<p class="description"><?php esc_html_e( 'Automatically detects and optimizes performance bottlenecks.', 'speeddash' ); ?></p>
										</td>
									</tr>
									
									<tr>
										<th scope="row"><?php esc_html_e( 'Aggressive Optimization', 'speeddash' ); ?></th>
										<td>
											<label>
												<input type="checkbox" name="speeddash_settings[speeddash_aggressive_optimization]" value="1" <?php checked( $settings['speeddash_aggressive_optimization'] ); ?>>
												<?php esc_html_e( 'Enable aggressive optimization mode', 'speeddash' ); ?>
											</label>
											<p class="description"><?php esc_html_e( 'More aggressive optimizations including early-stage optimizations.', 'speeddash' ); ?></p>
										</td>
									</tr>
									
									<tr>
										<th scope="row"><?php esc_html_e( 'Database Optimization', 'speeddash' ); ?></th>
										<td>
											<label>
												<input type="checkbox" name="speeddash_settings[speeddash_database_optimization]" value="1" <?php checked( $settings['speeddash_database_optimization'] ); ?>>
												<?php esc_html_e( 'Enable database optimization', 'speeddash' ); ?>
											</label>
											<p class="description"><?php esc_html_e( 'Optimizes database queries and cleans up expired data.', 'speeddash' ); ?></p>
										</td>
									</tr>
								</table>
							</div>

							<!-- SmartCache System -->
							<div class="speeddash-section">
								<h2><?php esc_html_e( 'SmartCache System', 'speeddash' ); ?></h2>
								<table class="form-table">
									<tr>
										<th scope="row"><?php esc_html_e( 'SmartCache', 'speeddash' ); ?></th>
										<td>
											<label>
												<input type="checkbox" name="speeddash_settings[speeddash_smartcache_enabled]" value="1" <?php checked( $settings['speeddash_smartcache_enabled'] ); ?>>
												<?php esc_html_e( 'Enable SmartCache (Redis-like file cache)', 'speeddash' ); ?>
											</label>
											<p class="description">
												<?php esc_html_e( 'Provides Redis-like performance using file-based caching. Only runs if no Redis/Memcached is detected.', 'speeddash' ); ?>
												<br>
												<strong><?php esc_html_e( 'Status:', 'speeddash' ); ?></strong> 
												<?php
												$cache_detection = get_option( 'speeddash_cache_detection', array() );
												$has_redis = ! empty( $cache_detection['redis']['detected'] );
												$has_memcached = ! empty( $cache_detection['memcached']['detected'] );
												$has_object_cache = ! empty( $cache_detection['object_cache']['detected'] );
												
												if ( $has_redis || $has_memcached || $has_object_cache ) {
													echo '<span style="color: #d63638;">' . esc_html__( 'Disabled - Better cache solution detected', 'speeddash' ) . '</span>';
												} else {
													echo '<span style="color: #00a32a;">' . esc_html__( 'Enabled - No better cache solution detected', 'speeddash' ) . '</span>';
												}
												?>
											</p>
										</td>
									</tr>

									<tr>
										<th scope="row"><?php esc_html_e( 'Prefetch Worker', 'speeddash' ); ?></th>
										<td>
											<label>
												<input type="checkbox" name="speeddash_settings[speeddash_prefetch_enabled]" value="1" <?php checked( $settings['speeddash_prefetch_enabled'] ); ?>>
												<?php esc_html_e( 'Enable background cache preloading', 'speeddash' ); ?>
											</label>
											<p class="description"><?php esc_html_e( 'Automatically preloads cache data in the background for faster page loads.', 'speeddash' ); ?></p>
										</td>
									</tr>
								</table>
							</div>

							<!-- Performance Monitoring -->
							<div class="speeddash-section">
								<h2><?php esc_html_e( 'Performance Monitoring', 'speeddash' ); ?></h2>
								<table class="form-table">
									<tr>
										<th scope="row"><?php esc_html_e( 'Performance Monitoring', 'speeddash' ); ?></th>
										<td>
											<label>
												<input type="checkbox" name="speeddash_settings[speeddash_performance_monitoring]" value="1" <?php checked( $settings['speeddash_performance_monitoring'] ); ?>>
												<?php esc_html_e( 'Enable performance monitoring', 'speeddash' ); ?>
											</label>
											<p class="description"><?php esc_html_e( 'Monitors and logs performance metrics for analysis.', 'speeddash' ); ?></p>
										</td>
									</tr>
								</table>
							</div>
						</div>
						
						<?php submit_button(); ?>
					</form>
				</div>

				<div class="speeddash-sidebar">
					<!-- Cache Status -->
					<div class="speeddash-widget">
						<h2><?php esc_html_e( 'Cache Status', 'speeddash' ); ?></h2>
						<?php $this->display_cache_status(); ?>
					</div>

					<!-- Quick Actions -->
					<div class="speeddash-widget">
						<h2><?php esc_html_e( 'Quick Actions', 'speeddash' ); ?></h2>
						<p>
							<a href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=speeddash_clear_cache' ), 'speeddash_clear_cache' ) ); ?>" 
							   class="button button-secondary" 
							   onclick="return confirm('<?php esc_attr_e( 'Are you sure you want to clear all cache?', 'speeddash' ); ?>');">
								<?php esc_html_e( 'Clear All Cache', 'speeddash' ); ?>
							</a>
						</p>
						<p>
							<a href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=speeddash_refresh_detection' ), 'speeddash_refresh_detection' ) ); ?>" 
							   class="button button-secondary">
								<?php esc_html_e( 'Refresh Cache Detection', 'speeddash' ); ?>
							</a>
						</p>
						<p>
							<a href="<?php echo esc_url( add_query_arg( 'restore', 'true', admin_url( 'index.php?page=speed-dash' ) ) ); ?>" 
							   class="button button-secondary" 
							   onclick="return confirm('<?php esc_attr_e( 'Are you sure you want to restore default settings?', 'speeddash' ); ?>');">
								<?php esc_html_e( 'Restore Defaults', 'speeddash' ); ?>
							</a>
						</p>
					</div>
				</div>
			</div>
		</div>

		<style>
		.speeddash-admin-wrapper {
			display: flex;
			gap: 20px;
			margin-top: 20px;
		}
		.speeddash-main-content {
			flex: 2;
		}
		.speeddash-sidebar {
			flex: 1;
		}
		.speeddash-section {
			background: #fff;
			border: 1px solid #ccd0d4;
			border-radius: 4px;
			margin-bottom: 20px;
			padding: 20px;
		}
		.speeddash-section h2 {
			margin-top: 0;
			border-bottom: 1px solid #eee;
			padding-bottom: 10px;
		}
		.speeddash-widget {
			background: #fff;
			border: 1px solid #ccd0d4;
			border-radius: 4px;
			padding: 15px;
			margin-bottom: 20px;
		}
		.speeddash-widget h2 {
			margin-top: 0;
			font-size: 14px;
		}
		.cache-status-excellent { color: #00a32a; font-weight: bold; }
		.cache-status-good { color: #00a32a; font-weight: bold; }
		.cache-status-fair { color: #dba617; font-weight: bold; }
		.cache-status-poor { color: #d63638; font-weight: bold; }
		</style>
		<?php
	}

	/**
	 * Display cache status.
	 *
	 * @since 1.0.0
	 */
	private function display_cache_status() {
		// Get cache detection results.
		$detection_results = get_option( 'speeddash_cache_detection', array() );
		
		// Get SmartCache stats if available.
		$smartcache_stats = array();
		$performance_metrics = array();
		$cache_health = array();
		
		if ( class_exists( 'Speed_Dash_SmartCache' ) ) {
			$smartcache_stats = Speed_Dash_SmartCache::get_stats();
			$performance_metrics = Speed_Dash_SmartCache::get_performance_metrics();
			$cache_health = Speed_Dash_SmartCache::get_cache_health();
		}
		
		?>
		<div class="speeddash-cache-status">
			<div class="cache-detection-results">
				<h3><?php esc_html_e( 'Cache Detection Results', 'speeddash' ); ?></h3>
				<table class="widefat">
					<thead>
						<tr>
							<th><?php esc_html_e( 'Cache Type', 'speeddash' ); ?></th>
							<th><?php esc_html_e( 'Status', 'speeddash' ); ?></th>
							<th><?php esc_html_e( 'Details', 'speeddash' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php
						$cache_types = array(
							'redis' => __( 'Redis', 'speeddash' ),
							'memcached' => __( 'Memcached', 'speeddash' ),
							'object_cache' => __( 'Object Cache', 'speeddash' ),
							'page_cache' => __( 'Page Cache', 'speeddash' ),
							'cdn' => __( 'CDN', 'speeddash' ),
							'varnish' => __( 'Varnish', 'speeddash' ),
							'cloudflare' => __( 'Cloudflare', 'speeddash' ),
						);
						
						foreach ( $cache_types as $type => $label ) {
							$result = isset( $detection_results[ $type ] ) ? $detection_results[ $type ] : array( 'detected' => false, 'details' => array() );
							$status = $result['detected'] ? '<span style="color: #00a32a;">✅ ' . __( 'Detected', 'speeddash' ) . '</span>' : '<span style="color: #d63638;">❌ ' . __( 'Not Detected', 'speeddash' ) . '</span>';
							$details = ! empty( $result['details'] ) ? implode( ', ', $result['details'] ) : __( 'None', 'speeddash' );
							?>
							<tr>
								<td><strong><?php echo esc_html( $label ); ?></strong></td>
								<td><?php echo $status; ?></td>
								<td><?php echo esc_html( $details ); ?></td>
							</tr>
							<?php
						}
						?>
					</tbody>
				</table>
			</div>

			<?php if ( ! empty( $smartcache_stats ) ) : ?>
			<div class="smartcache-stats">
				<h3><?php esc_html_e( 'SmartCache Statistics', 'speeddash' ); ?></h3>
				<table class="widefat">
					<thead>
						<tr>
							<th><?php esc_html_e( 'Metric', 'speeddash' ); ?></th>
							<th><?php esc_html_e( 'Runtime Cache', 'speeddash' ); ?></th>
							<th><?php esc_html_e( 'File Cache', 'speeddash' ); ?></th>
							<th><?php esc_html_e( 'Overall', 'speeddash' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><strong><?php esc_html_e( 'Hits', 'speeddash' ); ?></strong></td>
							<td><?php echo esc_html( $smartcache_stats['hybrid_cache']['runtime']['hits'] ); ?></td>
							<td><?php echo esc_html( $smartcache_stats['hybrid_cache']['file']['hits'] ); ?></td>
							<td><?php echo esc_html( $smartcache_stats['hybrid_cache']['hybrid']['total_hits'] ); ?></td>
						</tr>
						<tr>
							<td><strong><?php esc_html_e( 'Misses', 'speeddash' ); ?></strong></td>
							<td><?php echo esc_html( $smartcache_stats['hybrid_cache']['runtime']['misses'] ); ?></td>
							<td><?php echo esc_html( $smartcache_stats['hybrid_cache']['file']['misses'] ); ?></td>
							<td><?php echo esc_html( $smartcache_stats['hybrid_cache']['hybrid']['misses'] ); ?></td>
						</tr>
						<tr>
							<td><strong><?php esc_html_e( 'Hit Rate', 'speeddash' ); ?></strong></td>
							<td><?php echo esc_html( $smartcache_stats['hybrid_cache']['runtime']['hit_rate'] ); ?>%</td>
							<td><?php echo esc_html( $smartcache_stats['hybrid_cache']['file']['hit_rate'] ); ?>%</td>
							<td><?php echo esc_html( $smartcache_stats['hybrid_cache']['hybrid']['hit_rate'] ); ?>%</td>
						</tr>
						<tr>
							<td><strong><?php esc_html_e( 'Cache Size', 'speeddash' ); ?></strong></td>
							<td><?php echo esc_html( $smartcache_stats['hybrid_cache']['runtime']['cache_size'] ); ?> items</td>
							<td><?php echo esc_html( $smartcache_stats['hybrid_cache']['file']['file_count'] ); ?> files</td>
							<td><?php echo esc_html( $smartcache_stats['hybrid_cache']['file']['total_size_mb'] ); ?> MB</td>
						</tr>
					</tbody>
				</table>
			</div>

			<?php if ( ! empty( $performance_metrics ) ) : ?>
			<div class="performance-metrics">
				<h3><?php esc_html_e( 'Performance Metrics', 'speeddash' ); ?></h3>
				<table class="widefat">
					<tbody>
						<tr>
							<td><strong><?php esc_html_e( 'Cache Efficiency', 'speeddash' ); ?></strong></td>
							<td><?php echo esc_html( $performance_metrics['cache_efficiency'] ); ?>%</td>
						</tr>
						<tr>
							<td><strong><?php esc_html_e( 'Estimated Time Saved', 'speeddash' ); ?></strong></td>
							<td><?php echo esc_html( $performance_metrics['estimated_time_saved_ms'] ); ?> ms</td>
						</tr>
						<tr>
							<td><strong><?php esc_html_e( 'Performance Improvement', 'speeddash' ); ?></strong></td>
							<td><?php esc_html( $performance_metrics['estimated_time_saved_percentage'] ); ?>%</td>
						</tr>
					</tbody>
				</table>
			</div>
			<?php endif; ?>

			<?php if ( ! empty( $cache_health ) ) : ?>
			<div class="cache-health">
				<h3><?php esc_html_e( 'Cache Health', 'speeddash' ); ?></h3>
				<div class="cache-health-status">
					<p>
						<strong><?php esc_html_e( 'Status:', 'speeddash' ); ?></strong> 
						<span class="cache-status-<?php echo esc_attr( $cache_health['status'] ); ?>">
							<?php echo esc_html( ucfirst( $cache_health['status'] ) ); ?>
						</span>
						(<?php echo esc_html( $cache_health['score'] ); ?>/100)
					</p>
					
					<?php if ( ! empty( $cache_health['issues'] ) ) : ?>
					<div class="cache-issues">
						<h4><?php esc_html_e( 'Issues:', 'speeddash' ); ?></h4>
						<ul>
							<?php foreach ( $cache_health['issues'] as $issue ) : ?>
							<li><?php echo esc_html( $issue ); ?></li>
							<?php endforeach; ?>
						</ul>
					</div>
					<?php endif; ?>
					
					<?php if ( ! empty( $cache_health['recommendations'] ) ) : ?>
					<div class="cache-recommendations">
						<h4><?php esc_html_e( 'Recommendations:', 'speeddash' ); ?></h4>
						<ul>
							<?php foreach ( $cache_health['recommendations'] as $recommendation ) : ?>
							<li><?php echo esc_html( $recommendation ); ?></li>
							<?php endforeach; ?>
						</ul>
					</div>
					<?php endif; ?>
				</div>
			</div>
			<?php endif; ?>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Clear all cache.
	 *
	 * @since 1.0.0
	 */
	public function clear_cache() {
		// Check nonce.
		if ( ! wp_verify_nonce( $_GET['_wpnonce'], 'speeddash_clear_cache' ) ) {
			wp_die( esc_html__( 'Security check failed.', 'speeddash' ) );
		}

		// Check user capabilities.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to perform this action.', 'speeddash' ) );
		}

		// Clear SmartCache if available.
		if ( class_exists( 'Speed_Dash_SmartCache' ) ) {
			Speed_Dash_SmartCache::clear_all_cache();
		}

		// Clear WordPress transients.
		global $wpdb;
		$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_%' OR option_name LIKE '_site_transient_%'" );

		// Redirect with success message.
		wp_redirect( add_query_arg( 'cache-cleared', 'true', admin_url( 'index.php?page=speed-dash' ) ) );
		exit;
	}

	/**
	 * Refresh cache detection.
	 *
	 * @since 1.0.0
	 */
	public function refresh_detection() {
		// Check nonce.
		if ( ! wp_verify_nonce( $_GET['_wpnonce'], 'speeddash_refresh_detection' ) ) {
			wp_die( esc_html__( 'Security check failed.', 'speeddash' ) );
		}

		// Check user capabilities.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to perform this action.', 'speeddash' ) );
		}

		// Run cache detection.
		if ( class_exists( 'Speed_Dash_Cache_Detector' ) ) {
			$detector = new Speed_Dash_Cache_Detector();
			$detector->detect_cache_solutions();
		}

		// Redirect with success message.
		wp_redirect( add_query_arg( 'detection-refreshed', 'true', admin_url( 'index.php?page=speed-dash' ) ) );
		exit;
	}

	/**
	 * Get default settings.
	 *
	 * @since 1.0.0
	 */
	private function get_default_settings() {
		return array(
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
	}
}