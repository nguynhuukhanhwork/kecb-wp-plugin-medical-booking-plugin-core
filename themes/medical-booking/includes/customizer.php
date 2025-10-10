<?php
if (!defined('ABSPATH')) exit;

// Hook vào Customizer
add_action('customize_register', 'mb_customize_register_contact_info');
add_action('customize_register', 'mb_customize_register_header');
add_action('customize_register', 'mb_customize_register_footer');

/**
 * Contact Info
 */
function mb_customize_register_contact_info($wp_customize): void {
    $wp_customize->add_section('medical_contact', [
        'title' => __('Contact Info', 'medical-booking'),
        'priority' => 100,
    ]);

    // Phone
    $wp_customize->add_setting('medical_contact_phone', [
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('medical_contact_phone', [
        'label' => __('Phone Number', 'medical-booking'),
        'section' => 'medical_contact',
        'type' => 'tel',
    ]);

    // Email
    $wp_customize->add_setting('medical_contact_email', [
        'default' => '',
        'sanitize_callback' => 'sanitize_email',
    ]);
    $wp_customize->add_control('medical_contact_email', [
        'label' => __('Email Address', 'medical-booking'),
        'section' => 'medical_contact',
        'type' => 'email',
    ]);

    // Working Hours
    $wp_customize->add_setting('medical_contact_hours', [
        'default' => 'Mon-Fri: 8:00 AM - 6:00 PM',
        'sanitize_callback' => 'wp_kses_post',
    ]);
    $wp_customize->add_control('medical_contact_hours', [
        'label' => __('Working Hours', 'medical-booking'),
        'section' => 'medical_contact',
        'type' => 'textarea',
    ]);
}

/**
 * Header Settings
 */
function mb_customize_register_header($wp_customize): void {
    $wp_customize->add_section('medical_header', [
        'title' => __('Header Settings', 'medical-booking'),
        'priority' => 110,
    ]);

    $wp_customize->add_setting('medical_header_tagline', [
        'default' => 'Welcome to Medical Booking System',
        'sanitize_callback' => 'sanitize_text_field',
    ]);

    $wp_customize->add_control('medical_header_tagline', [
        'label'   => __('Header Tagline', 'medical-booking'),
        'section' => 'medical_header',
        'type'    => 'text',
    ]);
}

/**
 * Footer Settings
 */
function mb_customize_register_footer($wp_customize): void {
    $wp_customize->add_section('medical_footer', [
        'title'    => __('Footer Settings', 'medical-booking'),
        'priority' => 120,
    ]);

    $wp_customize->add_setting('medical_footer_text', [
        'default'   => '© 2025 Medical Booking System',
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ]);

    $wp_customize->add_control('medical_footer_text', [
        'label'   => __('Footer Text', 'medical-booking'),
        'section' => 'medical_footer',
        'type'    => 'text',
    ]);
}
