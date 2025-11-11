<?php

namespace TravelBooking\Presentation\Shortcodes;

use TravelBooking\Repository\TourRepository;

final class SearchTourShortcode
{
    private static ?self $instance = null;

    private function __construct(){
        add_shortcode('search-tour-by-name', array($this, 'searchTour'));
    }

    public static function getInstance(): self
    {
        return self::$instance ??= new self();
    }
    public function searchTour(): string {
        ob_start();
        require_once __DIR__ . "/Partials/_search_tour_name_form.php";
        return ob_get_clean();
    }

}