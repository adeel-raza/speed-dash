<?php
/**
 * WP Fusion - Prefetch Worker
 *
 * @package   WP Fusion
 * @copyright Copyright (c) 2024, Very Good Plugins, https://verygoodplugins.com
 * @license   GPL-3.0+
 * @since     3.37.14
 */

/**
 * Prefetch worker functionality.
 *
 * @package    Speed_Dash
 * @subpackage Speed_Dash/includes
 * @author     Your Name <email@example.com>
 */
class Speed_Dash_Prefetch_Worker {

	/**
	 * Hybrid cache instance.
	 */
	private $hybrid_cache;

	/**
	 * Constructor.
	 */
	public function __construct( $hybrid_cache ) {
		$this->hybrid_cache = $hybrid_cache;
		$this->init_hooks();
	}

	/**
	 * Initialize hooks.
	 */
	private function init_hooks() {
		add_action( 'wp_ajax_speeddash_prefetch', array( $this, 'prefetch_data' ) );
		add_action( 'wp_ajax_nopriv_speeddash_prefetch', array( $this, 'prefetch_data' ) );
		add_action( 'wp_cron', array( $this, 'schedule_prefetch' ) );
	}

	/**
	 * Prefetch data.
	 */
	public function prefetch_data() {
		// Prefetch common data.
		$this->prefetch_posts();
		$this->prefetch_pages();
		$this->prefetch_categories();
		$this->prefetch_tags();
		$this->prefetch_users();
		$this->prefetch_options();
	}

	/**
	 * Prefetch posts.
	 */
	private function prefetch_posts() {
		$posts = get_posts( array(
			'numberposts' => 100,
			'post_status' => 'publish',
		) );
		
		foreach ( $posts as $post ) {
			$key = 'post_' . $post->ID;
			$this->hybrid_cache->set( $key, $post, 3600 );
		}
	}

	/**
	 * Prefetch pages.
	 */
	private function prefetch_pages() {
		$pages = get_pages( array(
			'number' => 50,
			'post_status' => 'publish',
		) );
		
		foreach ( $pages as $page ) {
			$key = 'page_' . $page->ID;
			$this->hybrid_cache->set( $key, $page, 3600 );
		}
	}

	/**
	 * Prefetch categories.
	 */
	private function prefetch_categories() {
		$categories = get_categories( array(
			'hide_empty' => false,
		) );
		
		foreach ( $categories as $category ) {
			$key = 'category_' . $category->term_id;
			$this->hybrid_cache->set( $key, $category, 3600 );
		}
	}

	/**
	 * Prefetch tags.
	 */
	private function prefetch_tags() {
		$tags = get_tags( array(
			'hide_empty' => false,
		) );
		
		foreach ( $tags as $tag ) {
			$key = 'tag_' . $tag->term_id;
			$this->hybrid_cache->set( $key, $tag, 3600 );
		}
	}

	/**
	 * Prefetch users.
	 */
	private function prefetch_users() {
		$users = get_users( array(
			'number' => 50,
		) );
		
		foreach ( $users as $user ) {
			$key = 'user_' . $user->ID;
			$this->hybrid_cache->set( $key, $user, 3600 );
		}
	}

	/**
	 * Prefetch options.
	 */
	private function prefetch_options() {
		$options = array(
			'blogname',
			'blogdescription',
			'admin_email',
			'users_can_register',
			'default_role',
			'timezone_string',
			'date_format',
			'time_format',
			'start_of_week',
			'use_balanceTags',
			'default_category',
			'default_post_format',
			'mailserver_url',
			'mailserver_login',
			'mailserver_pass',
			'default_email_category',
			'comment_whitelist',
			'comment_registration',
			'html_type',
			'use_trackback',
			'default_role',
			'db_version',
			'uploads_use_yearmonth_folders',
			'upload_path',
			'blog_public',
			'default_link_category',
			'show_on_front',
			'tag_base',
			'show_avatars',
			'avatar_rating',
			'upload_url_path',
			'thumbnail_size_w',
			'thumbnail_size_h',
			'thumbnail_crop',
			'medium_size_w',
			'medium_size_h',
			'avatar_default',
			'large_size_w',
			'large_size_h',
			'image_default_link_type',
			'image_default_size',
			'image_default_align',
			'close_comments_for_old_posts',
			'close_comments_days_old',
			'thread_comments',
			'thread_comments_depth',
			'page_comments',
			'comments_per_page',
			'default_comments_page',
			'comment_order',
			'sticky_posts',
			'widget_categories',
			'widget_text',
			'widget_rss',
			'uninstall_plugins',
			'timezone_string',
			'blog_charset',
			'moderation_keys',
			'active_plugins',
			'category_base',
			'ping_sites',
			'comment_max_links',
			'moderation_notify',
			'permalink_structure',
			'rewrite_rules',
			'hack_file',
			'blog_charset',
			'moderation_keys',
			'active_plugins',
			'category_base',
			'ping_sites',
			'comment_max_links',
			'moderation_notify',
			'permalink_structure',
			'rewrite_rules',
			'hack_file',
			'blog_charset',
			'moderation_keys',
			'active_plugins',
			'category_base',
			'ping_sites',
			'comment_max_links',
			'moderation_notify',
			'permalink_structure',
			'rewrite_rules',
			'hack_file',
		);
		
		foreach ( $options as $option ) {
			$value = get_option( $option );
			$key = 'option_' . $option;
			$this->hybrid_cache->set( $key, $value, 3600 );
		}
	}

	/**
	 * Schedule prefetch.
	 */
	public function schedule_prefetch() {
		// Schedule prefetch every hour.
		if ( ! wp_next_scheduled( 'speeddash_prefetch_worker' ) ) {
			wp_schedule_event( time(), 'hourly', 'speeddash_prefetch_worker' );
		}
	}
}