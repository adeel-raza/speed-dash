<?php
/**
 * WP Fusion - Cache Detector
 *
 * @package   WP Fusion
 * @copyright Copyright (c) 2024, Very Good Plugins, https://verygoodplugins.com
 * @license   GPL-3.0+
 * @since     3.37.14
 */

/**
 * Cache detection functionality.
 *
 * @package    Speed_Dash
 * @subpackage Speed_Dash/includes
 * @author     Your Name <email@example.com>
 */
class Speed_Dash_Cache_Detector {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'detect_cache_solutions' ) );
	}

	/**
	 * Detect cache solutions.
	 */
	public function detect_cache_solutions() {
		$detection_results = array(
			'redis' => $this->detect_redis(),
			'memcached' => $this->detect_memcached(),
			'object_cache' => $this->detect_object_cache(),
			'page_cache' => $this->detect_page_cache(),
			'cdn' => $this->detect_cdn(),
			'varnish' => $this->detect_varnish(),
			'cloudflare' => $this->detect_cloudflare(),
		);

		// Store detection results.
		update_option( 'speeddash_cache_detection', $detection_results );

		// Store active caches.
		$active_caches = $this->get_active_caches_from_results( $detection_results );
		update_option( 'speeddash_active_caches', $active_caches );

		return $detection_results;
	}

	/**
	 * Detect Redis.
	 */
	private function detect_redis() {
		$detected = false;
		$details = array();

		// Check if Redis extension is loaded.
		if ( extension_loaded( 'redis' ) ) {
			$detected = true;
			$details[] = 'Redis extension loaded';
		}

		// Check if Redis is available via wp_cache.
		if ( function_exists( 'wp_cache_get' ) ) {
			$test_key = 'speeddash_redis_test_' . time();
			$test_value = 'test_value';
			
			if ( wp_cache_set( $test_key, $test_value, '', 60 ) && wp_cache_get( $test_key ) === $test_value ) {
				$detected = true;
				$details[] = 'Redis working via wp_cache';
			}
		}

		// Check for Redis object cache drop-in.
		if ( file_exists( WP_CONTENT_DIR . '/object-cache.php' ) ) {
			$object_cache_content = file_get_contents( WP_CONTENT_DIR . '/object-cache.php' );
			if ( strpos( $object_cache_content, 'redis' ) !== false || strpos( $object_cache_content, 'Redis' ) !== false ) {
				$detected = true;
				$details[] = 'Redis object cache drop-in detected';
			}
		}

		return array(
			'detected' => $detected,
			'details' => $details,
		);
	}

	/**
	 * Detect Memcached.
	 */
	private function detect_memcached() {
		$detected = false;
		$details = array();

		// Check if Memcached extension is loaded.
		if ( extension_loaded( 'memcached' ) || extension_loaded( 'memcache' ) ) {
			$detected = true;
			$details[] = 'Memcached extension loaded';
		}

		// Check for Memcached object cache drop-in.
		if ( file_exists( WP_CONTENT_DIR . '/object-cache.php' ) ) {
			$object_cache_content = file_get_contents( WP_CONTENT_DIR . '/object-cache.php' );
			if ( strpos( $object_cache_content, 'memcached' ) !== false || strpos( $object_cache_content, 'Memcached' ) !== false ) {
				$detected = true;
				$details[] = 'Memcached object cache drop-in detected';
			}
		}

		return array(
			'detected' => $detected,
			'details' => $details,
		);
	}

	/**
	 * Detect object cache.
	 */
	private function detect_object_cache() {
		$detected = false;
		$details = array();

		// Check if object cache drop-in exists.
		if ( file_exists( WP_CONTENT_DIR . '/object-cache.php' ) ) {
			$detected = true;
			$details[] = 'Object cache drop-in exists';
		}

		// Check if wp_cache functions are available.
		if ( function_exists( 'wp_cache_get' ) && function_exists( 'wp_cache_set' ) ) {
			$detected = true;
			$details[] = 'wp_cache functions available';
		}

		return array(
			'detected' => $detected,
			'details' => $details,
		);
	}

	/**
	 * Detect page cache.
	 */
	private function detect_page_cache() {
		$detected = false;
		$details = array();

		// Check for common page cache plugins.
		$page_cache_plugins = array(
			'wp-super-cache/wp-super-cache.php',
			'w3-total-cache/w3-total-cache.php',
			'wp-rocket/wp-rocket.php',
			'wp-fastest-cache/wpFastestCache.php',
			'litespeed-cache/litespeed-cache.php',
			'wp-optimize/wp-optimize.php',
		);

		$active_plugins = get_option( 'active_plugins', array() );
		foreach ( $page_cache_plugins as $plugin ) {
			if ( in_array( $plugin, $active_plugins, true ) ) {
				$detected = true;
				$details[] = 'Page cache plugin: ' . $plugin;
			}
		}

		// Check for cache headers.
		if ( function_exists( 'headers_list' ) ) {
			$headers = headers_list();
			foreach ( $headers as $header ) {
				if ( stripos( $header, 'cache-control' ) !== false || stripos( $header, 'expires' ) !== false ) {
					$detected = true;
					$details[] = 'Cache headers detected';
					break;
				}
			}
		}

		return array(
			'detected' => $detected,
			'details' => $details,
		);
	}

	/**
	 * Detect CDN.
	 */
	private function detect_cdn() {
		$detected = false;
		$details = array();

		// Check for CDN plugins.
		$cdn_plugins = array(
			'wp-rocket/wp-rocket.php',
			'w3-total-cache/w3-total-cache.php',
			'wp-super-cache/wp-super-cache.php',
			'cloudflare/cloudflare.php',
		);

		$active_plugins = get_option( 'active_plugins', array() );
		foreach ( $cdn_plugins as $plugin ) {
			if ( in_array( $plugin, $active_plugins, true ) ) {
				$detected = true;
				$details[] = 'CDN plugin: ' . $plugin;
			}
		}

		// Check for CDN URLs in content.
		$cdn_domains = array( 'cloudflare.com', 'jsdelivr.net', 'cdnjs.cloudflare.com', 'unpkg.com' );
		$site_url = get_site_url();
		foreach ( $cdn_domains as $domain ) {
			if ( strpos( $site_url, $domain ) !== false ) {
				$detected = true;
				$details[] = 'CDN domain detected: ' . $domain;
			}
		}

		return array(
			'detected' => $detected,
			'details' => $details,
		);
	}

	/**
	 * Detect Varnish.
	 */
	private function detect_varnish() {
		$detected = false;
		$details = array();

		// Check for Varnish headers.
		if ( function_exists( 'headers_list' ) ) {
			$headers = headers_list();
			foreach ( $headers as $header ) {
				if ( stripos( $header, 'x-varnish' ) !== false || stripos( $header, 'via' ) !== false ) {
					$detected = true;
					$details[] = 'Varnish headers detected';
					break;
				}
			}
		}

		// Check for Varnish plugins.
		$varnish_plugins = array(
			'varnish-http-purge/varnish-http-purge.php',
			'varnish-cache/varnish-cache.php',
		);

		$active_plugins = get_option( 'active_plugins', array() );
		foreach ( $varnish_plugins as $plugin ) {
			if ( in_array( $plugin, $active_plugins, true ) ) {
				$detected = true;
				$details[] = 'Varnish plugin: ' . $plugin;
			}
		}

		return array(
			'detected' => $detected,
			'details' => $details,
		);
	}

	/**
	 * Detect Cloudflare.
	 */
	private function detect_cloudflare() {
		$detected = false;
		$details = array();

		// Check for Cloudflare headers.
		if ( function_exists( 'headers_list' ) ) {
			$headers = headers_list();
			foreach ( $headers as $header ) {
				if ( stripos( $header, 'cf-' ) !== false || stripos( $header, 'cloudflare' ) !== false ) {
					$detected = true;
					$details[] = 'Cloudflare headers detected';
					break;
				}
			}
		}

		// Check for Cloudflare plugins.
		$cloudflare_plugins = array(
			'cloudflare/cloudflare.php',
			'cloudflare-flexible-ssl/cloudflare-flexible-ssl.php',
		);

		$active_plugins = get_option( 'active_plugins', array() );
		foreach ( $cloudflare_plugins as $plugin ) {
			if ( in_array( $plugin, $active_plugins, true ) ) {
				$detected = true;
				$details[] = 'Cloudflare plugin: ' . $plugin;
			}
		}

		return array(
			'detected' => $detected,
			'details' => $details,
		);
	}

	/**
	 * Get active caches from detection results.
	 */
	private function get_active_caches_from_results( $detection_results ) {
		$active_caches = array();

		foreach ( $detection_results as $cache_type => $result ) {
			if ( $result['detected'] ) {
				$active_caches[] = $cache_type;
			}
		}

		return $active_caches;
	}

	/**
	 * Check if SmartCache should be enabled.
	 */
	public static function should_enable_smartcache() {
		$detection_results = get_option( 'speeddash_cache_detection', array() );
		
		// Don't enable SmartCache if Redis or Memcached is detected.
		if ( ! empty( $detection_results['redis']['detected'] ) || ! empty( $detection_results['memcached']['detected'] ) ) {
			return false;
		}

		// Don't enable SmartCache if object cache is detected.
		if ( ! empty( $detection_results['object_cache']['detected'] ) ) {
			return false;
		}

		return true;
	}
}