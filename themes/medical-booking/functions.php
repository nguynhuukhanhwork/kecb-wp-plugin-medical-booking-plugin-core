<?php
/**
 * Medical Booking Theme Functions
 * Clean, simple, KISS approach
 */

if (!defined('ABSPATH')) exit;

class MedicalBookingTheme {

    private $theme_version;

    public function __construct() {
        $this->theme_version = wp_get_theme()->get('Version');

        // Core theme setup
        add_action('after_setup_theme', [$this, 'theme_setup']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('widgets_init', [$this, 'register_widget_areas']);

        // Theme specific
        add_filter('body_class', [$this, 'custom_body_classes']);

        // Load includes
        $this->load_includes();
    }

    /**
     * Theme setup - consolidated
     */
    public function theme_setup(): void {
        // Basic WordPress features
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');
        add_theme_support('html5', [
            'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
        ]);
        add_theme_support('responsive-embeds');
        add_theme_support('customize-selective-refresh-widgets');

        // Custom logo and backgrounds
        add_theme_support('custom-logo', [
            'height' => 60,
            'width' => 200,
            'flex-width' => true,
            'flex-height' => true,
        ]);
        add_theme_support('custom-background');

        // Block Editor support (theme.json handles most of this)
        add_theme_support('wp-block-styles');
        add_theme_support('align-wide');
        add_theme_support('editor-styles');

        // Medical-specific image sizes
        add_image_size('doctor-avatar', 150, 150, true);
        add_image_size('clinic-banner', 1200, 400, true);

        // Navigation menus
        register_nav_menus([
            'primary' => __('Primary Menu', 'medical-booking'),
            'footer' => __('Footer Menu', 'medical-booking'),
            'mobile' => __('Mobile Menu', 'medical-booking')
        ]);

        // Load text domain
        load_theme_textdomain('medical-booking', get_template_directory() . '/languages');
    }

    /**
     * Enqueue styles and scripts - optimized
     */
    public function enqueue_assets(): void {
        // Main stylesheet (style.css)
        wp_enqueue_style(
            'medical-booking-style',
            get_stylesheet_uri(),
            [],
            $this->theme_version
        );

        // Main JavaScript
        wp_enqueue_script(
            'medical-booking-main',
            get_template_directory_uri() . '/assets/js/main.js',
            ['jquery'],
            $this->theme_version,
            true
        );

        // Conditional loading for specific pages
        $this->conditional_assets();

        // Localize script for AJAX
        wp_localize_script('medical-booking-main', 'medicalBooking', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('medical_booking_nonce'),
            'messages' => [
                'loading' => __('Loading...', 'medical-booking'),
                'error' => __('Something went wrong', 'medical-booking'),
                'success' => __('Success!', 'medical-booking')
            ]
        ]);
    }

    /**
     * Load assets based on page context
     */
    private function conditional_assets(): void {
        global $post;

        // Booking form assets
        if (is_page('booking') || (isset($post->post_content) && has_shortcode($post->post_content, 'booking_form'))) {
            wp_enqueue_style(
                'booking-form',
                get_template_directory_uri() . '/assets/css/booking-form.css',
                ['medical-booking-style'],
                $this->theme_version
            );

            wp_enqueue_script(
                'booking-form',
                get_template_directory_uri() . '/assets/js/booking-form.js',
                ['medical-booking-main'],
                $this->theme_version,
                true
            );
        }

        // Dashboard assets
        if (is_user_logged_in() && (is_page('dashboard') || is_page('appointments'))) {
            wp_enqueue_style(
                'dashboard',
                get_template_directory_uri() . '/assets/css/dashboard.css',
                ['medical-booking-style'],
                $this->theme_version
            );
        }
    }

    /**
     * Register widget areas - simplified
     */
    public function register_widget_areas(): void {

    }

    /**
     * Custom body classes
     */
    public function custom_body_classes($classes): array {
        // Add medical booking specific classes
        if (is_singular('doctor')) {
            $classes[] = 'single-doctor';
        }

        if (is_post_type_archive('doctor')) {
            $classes[] = 'doctors-archive';
        }

        if (is_page('booking')) {
            $classes[] = 'booking-page';
        }

        if (is_user_logged_in()) {
            $classes[] = 'user-logged-in';
        }

        return $classes;
    }

    /**
     * Load theme includes
     */
    private function load_includes(): void {
        $includes = [
            '/includes/elementor-support.php',
            '/includes/customizer.php',
            '/includes/nav-menu.php',
            '/includes/widgets.php',
        ];

        foreach ($includes as $file) {
            $filepath = get_template_directory() . $file;
            if (file_exists($filepath)) {
                require_once $filepath;
            }
        }
    }
}

// Initialize theme
new MedicalBookingTheme();

/**
 * Simple block patterns registration
 */
function medical_booking_register_patterns(): void {
    // Register pattern category first
    register_block_pattern_category('medical-booking', [
        'label' => __('Medical Booking', 'medical-booking')
    ]);

    // Simple booking form pattern
    register_block_pattern('medical-booking/booking-form', [
        'title' => __('Booking Form', 'medical-booking'),
        'description' => __('Simple appointment booking form', 'medical-booking'),
        'categories' => ['medical-booking'],
        'content' => '<!-- wp:group {"className":"booking-form-container"} -->
                     <div class="wp-block-group booking-form-container">
                         <!-- wp:heading {"level":2} -->
                         <h2>' . __('Book Your Appointment', 'medical-booking') . '</h2>
                         <!-- /wp:heading -->
                         
                         <!-- wp:shortcode -->
                         [medical_booking_form]
                         <!-- /wp:shortcode -->
                     </div>
                     <!-- /wp:group -->'
    ]);

    // Simple doctor list pattern
    register_block_pattern('medical-booking/doctor-grid', [
        'title' => __('Doctor Grid', 'medical-booking'),
        'description' => __('Grid layout for displaying doctors', 'medical-booking'),
        'categories' => ['medical-booking'],
        'content' => '<!-- wp:group {"className":"doctors-grid"} -->
                     <div class="wp-block-group doctors-grid">
                         <!-- wp:heading {"level":2} -->
                         <h2>' . __('Our Doctors', 'medical-booking') . '</h2>
                         <!-- /wp:heading -->
                         
                         <!-- wp:shortcode -->
                         [medical_doctors_list]
                         <!-- /wp:shortcode -->
                     </div>
                     <!-- /wp:group -->'
    ]);
}
add_action('init', 'medical_booking_register_patterns');

/**
 * Simple AJAX handler for booking forms
 */
function medical_booking_ajax_handler(): void {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'] ?? '', 'medical_booking_nonce')) {
        wp_send_json_error(['message' => 'Security check failed']);
    }

    // Basic validation
    $required_fields = ['patient_name', 'patient_phone', 'appointment_date'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            wp_send_json_error(['message' => "Field {$field} is required"]);
        }
    }

    // Process booking (integrate with your core system)
    $booking_data = [
        'patient_name' => sanitize_text_field($_POST['patient_name']),
        'patient_phone' => sanitize_text_field($_POST['patient_phone']),
        'appointment_date' => sanitize_text_field($_POST['appointment_date']),
        'doctor_id' => intval($_POST['doctor_id'] ?? 0),
    ];

    // Here you would call your core booking system
    // do_action('medical_booking_process', $booking_data);

    wp_send_json_success(['message' => 'Appointment booked successfully!']);
}
add_action('wp_ajax_medical_booking_submit', 'medical_booking_ajax_handler');
add_action('wp_ajax_nopriv_medical_booking_submit', 'medical_booking_ajax_handler');




