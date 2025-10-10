<?php
/**
 * Simple Elementor Support
 */

if (!defined('ABSPATH')) exit;

// Add Elementor support
function medical_booking_elementor_support(): void {
    add_theme_support('elementor-header-footer');
    add_theme_support('elementor');
}
add_action('after_setup_theme', 'medical_booking_elementor_support');

// Register Elementor locations
function medical_booking_elementor_locations($elementor_theme_manager): void {
    $elementor_theme_manager->register_location('header');
    $elementor_theme_manager->register_location('footer');
}
add_action('elementor/theme/register_locations', 'medical_booking_elementor_locations');

// Enqueue Elementor custom CSS
function medical_booking_elementor_css(): void {
    wp_enqueue_style(
        'medical-elementor-custom',
        get_template_directory_uri() . '/assets/css/elementor.css',
        ['elementor-frontend'],
        wp_get_theme()->get('Version')
    );
}
add_action('elementor/frontend/after_enqueue_styles', 'medical_booking_elementor_css');