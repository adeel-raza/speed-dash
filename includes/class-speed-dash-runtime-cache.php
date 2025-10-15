<?php
/**
 * WP Fusion - Runtime Cache
 *
 * @package   WP Fusion
 * @copyright Copyright (c) 2024, Very Good Plugins, https://verygoodplugins.com
 * @license   GPL-3.0+
 * @since     3.37.14
 */

/**
 * Runtime cache functionality.
 *
 * @package    Speed_Dash
 * @subpackage Speed_Dash/includes
 * @author     Your Name <email@example.com>
 */
class Speed_Dash_Runtime_Cache {

	/**
	 * Cache storage.
	 */
	private static $cache = array();

	/**
	 * Cache statistics.
	 */
	private static $stats = array(
		'hits' => 0,
		'misses' => 0,
	);

	/**
	 * Get value from cache.
	 */
	public static function get( $key ) {
		if ( isset( self::$cache[ $key ] ) ) {
			self::$stats['hits']++;
			return self::$cache[ $key ];
		}

		self::$stats['misses']++;
		return false;
	}

	/**
	 * Set value in cache.
	 */
	public static function set( $key, $value, $expiration = 3600 ) {
		self::$cache[ $key ] = $value;
		return true;
	}

	/**
	 * Delete value from cache.
	 */
	public static function delete( $key ) {
		if ( isset( self::$cache[ $key ] ) ) {
			unset( self::$cache[ $key ] );
			return true;
		}
		return false;
	}

	/**
	 * Clear all cache.
	 */
	public static function clear_all() {
		self::$cache = array();
		self::$stats = array(
			'hits' => 0,
			'misses' => 0,
		);
	}

	/**
	 * Get cache statistics.
	 */
	public static function get_stats() {
		$total_requests = self::$stats['hits'] + self::$stats['misses'];
		$hit_rate = $total_requests > 0 ? round( ( self::$stats['hits'] / $total_requests ) * 100, 2 ) : 0;

		return array(
			'hits' => self::$stats['hits'],
			'misses' => self::$stats['misses'],
			'hit_rate' => $hit_rate,
			'cache_size' => count( self::$cache ),
		);
	}
}