<?php
/**
 * WP Fusion - File Cache
 *
 * @package   WP Fusion
 * @copyright Copyright (c) 2024, Very Good Plugins, https://verygoodplugins.com
 * @license   GPL-3.0+
 * @since     3.37.14
 */

/**
 * File cache functionality.
 *
 * @package    Speed_Dash
 * @subpackage Speed_Dash/includes
 * @author     Your Name <email@example.com>
 */
class Speed_Dash_File_Cache {

	/**
	 * Cache directory.
	 */
	private static $cache_dir;

	/**
	 * Cache statistics.
	 */
	private static $stats = array(
		'hits' => 0,
		'misses' => 0,
	);

	/**
	 * Initialize cache directory.
	 */
	private static function init_cache_dir() {
		if ( ! self::$cache_dir ) {
			self::$cache_dir = WP_CONTENT_DIR . '/cache/speed-dash/';
			if ( ! file_exists( self::$cache_dir ) ) {
				wp_mkdir_p( self::$cache_dir );
			}
		}
	}

	/**
	 * Get cache file path.
	 */
	private static function get_cache_file_path( $key ) {
		self::init_cache_dir();
		return self::$cache_dir . md5( $key ) . '.cache';
	}

	/**
	 * Get value from cache.
	 */
	public static function get( $key ) {
		$cache_file = self::get_cache_file_path( $key );

		if ( ! file_exists( $cache_file ) ) {
			self::$stats['misses']++;
			return false;
		}

		$cache_data = file_get_contents( $cache_file );
		$cache_data = unserialize( $cache_data );

		if ( ! $cache_data || $cache_data['expires'] < time() ) {
			self::$stats['misses']++;
			unlink( $cache_file );
			return false;
		}

		self::$stats['hits']++;
		return $cache_data['value'];
	}

	/**
	 * Set value in cache.
	 */
	public static function set( $key, $value, $expiration = 3600 ) {
		$cache_file = self::get_cache_file_path( $key );
		$cache_data = array(
			'value' => $value,
			'expires' => time() + $expiration,
		);

		return file_put_contents( $cache_file, serialize( $cache_data ) ) !== false;
	}

	/**
	 * Delete value from cache.
	 */
	public static function delete( $key ) {
		$cache_file = self::get_cache_file_path( $key );
		if ( file_exists( $cache_file ) ) {
			return unlink( $cache_file );
		}
		return false;
	}

	/**
	 * Clear all cache.
	 */
	public static function clear_all() {
		self::init_cache_dir();
		$files = glob( self::$cache_dir . '*.cache' );
		foreach ( $files as $file ) {
			unlink( $file );
		}
		self::$stats = array(
			'hits' => 0,
			'misses' => 0,
		);
	}

	/**
	 * Get cache statistics.
	 */
	public static function get_stats() {
		self::init_cache_dir();
		$files = glob( self::$cache_dir . '*.cache' );
		$total_size = 0;
		foreach ( $files as $file ) {
			$total_size += filesize( $file );
		}

		$total_requests = self::$stats['hits'] + self::$stats['misses'];
		$hit_rate = $total_requests > 0 ? round( ( self::$stats['hits'] / $total_requests ) * 100, 2 ) : 0;

		return array(
			'hits' => self::$stats['hits'],
			'misses' => self::$stats['misses'],
			'hit_rate' => $hit_rate,
			'file_count' => count( $files ),
			'total_size_mb' => round( $total_size / 1024 / 1024, 2 ),
		);
	}
}