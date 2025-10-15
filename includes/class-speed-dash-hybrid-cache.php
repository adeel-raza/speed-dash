<?php
/**
 * WP Fusion - Hybrid Cache
 *
 * @package   WP Fusion
 * @copyright Copyright (c) 2024, Very Good Plugins, https://verygoodplugins.com
 * @license   GPL-3.0+
 * @since     3.37.14
 */

/**
 * Hybrid cache functionality.
 *
 * @package    Speed_Dash
 * @subpackage Speed_Dash/includes
 * @author     Your Name <email@example.com>
 */
class Speed_Dash_Hybrid_Cache {

	/**
	 * Runtime cache instance.
	 */
	private $runtime_cache;

	/**
	 * File cache instance.
	 */
	private $file_cache;

	/**
	 * Constructor.
	 */
	public function __construct( $runtime_cache, $file_cache ) {
		$this->runtime_cache = $runtime_cache;
		$this->file_cache = $file_cache;
	}

	/**
	 * Get value from cache.
	 */
	public function get( $key ) {
		// Try runtime cache first.
		$value = $this->runtime_cache->get( $key );
		if ( $value !== false ) {
			return $value;
		}

		// Try file cache.
		$value = $this->file_cache->get( $key );
		if ( $value !== false ) {
			// Store in runtime cache for next time.
			$this->runtime_cache->set( $key, $value );
			return $value;
		}

		return false;
	}

	/**
	 * Set value in cache.
	 */
	public function set( $key, $value, $expiration = 3600 ) {
		// Set in both caches.
		$this->runtime_cache->set( $key, $value, $expiration );
		$this->file_cache->set( $key, $value, $expiration );
		return true;
	}

	/**
	 * Delete value from cache.
	 */
	public function delete( $key ) {
		$this->runtime_cache->delete( $key );
		$this->file_cache->delete( $key );
		return true;
	}

	/**
	 * Clear all cache.
	 */
	public function clear_all() {
		$this->runtime_cache->clear_all();
		$this->file_cache->clear_all();
		return true;
	}
}