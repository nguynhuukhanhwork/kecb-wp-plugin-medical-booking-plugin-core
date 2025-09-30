<?php

namespace MedicalBooking\Presentation\shortcodes;

use function MedicalBooking\Helpers\kecb_write_error_log;

final class MB_Search_Form_Shortcode
{
    private string $form_template_file = MB_PRESENTATION_PATH . 'form/templates/form-search.php';
    private static ?self $instance = null;

    private function __construct()
    {
        add_shortcode('ecb_search_form', [$this, 'renderForm']);
        add_action('wp_enqueue_scripts', [$this, 'enqueueScripts']);
    }

    private function __clone() {}
    private function __wakeup() {}

    public static function get_instance(): self
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function enqueueScripts(): void {
        $asset_url = MB_PRESENTATION_URL . 'assets/';

        wp_enqueue_style(
            'mb-search-style',
            $asset_url . 'css/mb-search-form-style.css'
        );

        wp_enqueue_script(
            'mb-search-script-ajax',
            $asset_url . 'js/mb-search-form-ajax.js',
            ['jquery'],
            '1.0',
            true
        );

        // Localize script to pass REST API URL and nonce to JavaScript.
        wp_localize_script('mb-search-script-ajax', 'MB_Search', [
            'restUrl' => esc_url_raw(rest_url('mb/v1/search')),
        ]);
    }

    /**
     * Render HTML Form
     * @param array $atts shortcode config
     * @return string
     */
    public function renderForm($atts): string
    {
        $form_template_file = $this->form_template_file;

        $allowed_post_type = ['doctor', 'service'];

        $atts = shortcode_atts([
            'post_type'   => 'post',
            'placeholder' => 'Nhập từ khóa tìm kiếm...',
            'button_text' => 'Tìm kiếm'
        ], $atts);

        // Chuẩn hóa context (view model)
        $context = [
            'post_type'   => $atts['post_type'],
            'placeholder' => $atts['placeholder'],
            'button_text' => $atts['button_text']
        ];

        if (! in_array($atts['post_type'], $allowed_post_type, true)) {
            return '<p>Tìm kiếm không hợp lệ</p>';
        }

        ob_start();

        // Load form
        if (file_exists($form_template_file)) {
            include $form_template_file; // dùng include thay vì require_once
        } else {
            kecb_write_error_log("File not found: " . $form_template_file);
        }

        return ob_get_clean();
    }
}

MB_Search_Form_Shortcode::get_instance();