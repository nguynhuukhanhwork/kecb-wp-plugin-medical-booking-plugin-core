<?php
/**
 * Astra child theme Theme functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package astra-child
 */

add_action( 'wp_enqueue_scripts', 'astra_child_parent_theme_enqueue_styles' );

/**
 * Enqueue scripts and styles.
 */
function astra_child_parent_theme_enqueue_styles() {
	wp_enqueue_style( 'astra-child-style', get_template_directory_uri() . '/style.css', array(), '0.1.0' );
	wp_enqueue_style(
		'astra-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		array( 'astra-child-style' ),
		'0.1.0'
	);
}

// Include doctor helper functions
require_once get_stylesheet_directory() . '/doctor-helpers.php';

function astra_child_enqueue_bootstrap(): void {
	// Enqueue Bootstrap CSS (sử dụng CDN cho tốc độ, hoặc local để offline)
	// wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css', array(), '5.3.3', 'all');

	// Enqueue Bootstrap JS (bundle bao gồm Popper)
	// wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', array('jquery'), '5.3.3', true);

	wp_enqueue_style('bootstrap-css', get_stylesheet_directory_uri() . '/assets/bootstrap/css/bootstrap.min.css', array(), '5.3.3', 'all');
	wp_enqueue_script('bootstrap-js', get_stylesheet_directory_uri() . '/assets/bootstrap/js/bootstrap.bundle.min.js', array('jquery'), '5.3.3', true);
}

add_action('wp_enqueue_scripts', 'astra_child_enqueue_bootstrap', 15);

// Include debug helper (only for development)
if (defined('WP_DEBUG') && WP_DEBUG) {
    require_once get_stylesheet_directory() . '/debug-doctor-data.php';
}
