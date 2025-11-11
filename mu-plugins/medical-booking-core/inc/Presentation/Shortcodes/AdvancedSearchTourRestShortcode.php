<?php

namespace TravelBooking\Presentation\Shortcodes;

use TravelBooking\Repository\TourRepository;

final class AdvancedSearchTourRestShortcode
{
    private static ?self $instance = null;

    private function __construct()
    {
        add_shortcode('advanced_search_tour', [$this, 'formSearch']);
        // add_action('wp_enqueue_scripts', [$this, 'enqueueScripts']);
    }

    private function __clone()
    {
    }

    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize a singleton class " . __CLASS__);
    }

    public static function getInstance(): self
    {
        return self::$instance ?? (self::$instance = new self());
    }

    public function formSearch(): string
    {

        // Get data
        $tour_repository = TourRepository::getInstance();

        $tour_locations = $tour_repository->geTourLocationTermNames();
        $tour_types = $tour_repository->getTourTypeTermNames();
        $tour_persons = $tour_repository->getTourPersonTermNames();
        $tour_linked = $tour_repository->getTourLinkedTermNames();

        $form_action = '';

        ob_start();
        require_once __DIR__ . "/Partials/_advanced_search_form.php";
        return ob_get_clean();
    }
}