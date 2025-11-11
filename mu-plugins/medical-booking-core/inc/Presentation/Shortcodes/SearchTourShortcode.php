<?php

namespace TravelBooking\Presentation\Shortcodes;

use TravelBooking\Repository\TourRepository;

final class SearchTourShortcode
{
    private static ?self $instance = null;

    private function __construct(){
        add_shortcode('search-tour', array($this, 'searchTour'));
    }

    public static function getInstance(): self
    {
        return self::$instance ??= new self();
    }
    public function searchTour() {
        $data = TourRepository::getInstance()->filterAdvancedTour();

        foreach($data as $tour) {
            $entity = TourRepository::getInstance()->mapToEntity($tour);

        }
    }

}