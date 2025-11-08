<?php

namespace TravelBooking\Presentation\Shortcodes;

final class SearchTourShortcode
{
    private static ?self $instance = null;

    private function __construct()
    {
        add_shortcode('travel_search_tour', [$this, 'searchTour']);
        add_action('wp_enqueue_scripts', [$this, 'enqueueScripts']);
    }
    private function __clone(){}
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize a singleton class " . __CLASS__);
    }

    public static function getInstance(): self
    {
        return self::$instance ?? (self::$instance = new self());
    }

    public function enqueueScripts(): void
    {
        wp_register_script(
                'travel-booking-search-tour',
                TB_PRESENTATION_LAYER_URL . 'Assets/js/search-tour.js',
                ['jquery'],
                '1.0',
                true
        );


        wp_localize_script(
                'travel-booking-search-tour',
                'travelBookingSearchTour',
                [
                        'api_url' => esc_url(rest_url('travel-booking/v1/tours/search')),
                        'nonce'   => wp_create_nonce('wp_rest'),
                ]
        );

        wp_enqueue_script('travel-booking-search-tour');
    }

    public function searchTour(): string {
        ob_start(); ?>
        <div class="travel-search-tour">
            <input
                    type="search"
                    class="travel-tour-search-input"
                    id="travel-tour-search"
                    placeholder="<?php esc_attr_e('Tìm tour du lịch...', 'travel-booking'); ?>"
            >
            <button type="button" id="travel-tour-search-btn">
                <?php esc_html_e('Tìm kiếm', 'travel-booking'); ?>
            </button>

            <div id="travel-tour-results"></div>
        </div>
        <?php
        return ob_get_clean();
    }
}