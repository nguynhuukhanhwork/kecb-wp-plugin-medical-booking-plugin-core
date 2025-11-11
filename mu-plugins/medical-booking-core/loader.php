<?php

use TravelBooking\Infrastructure\Database\BookingIndexTable;
use TravelBooking\Infrastructure\Database\BookingMetaTable;
use TravelBooking\Infrastructure\Database\CustomerTable;
use TravelBooking\Infrastructure\Database\NotificationTable;
use TravelBooking\Infrastructure\Database\TourSchedulerTable;
use TravelBooking\Infrastructure\Integrations\CF7\RegistrarTagOptions;
use TravelBooking\Infrastructure\WordPress\Registry\TaxonomyRegistry;
use TravelBooking\Presentation\Rest\TourSearchRestController;
use TravelBooking\Repository\TourRepository;

/** Autoload File */
require_once __DIR__.'/vendor/autoload.php';
/** Load Const */
require_once __DIR__.'/constant.php';

new \TravelBooking\Presentation\Rest\TourSearchRestController();
function tour_booking_system_register_wordpress_infrastructure() {
    \TravelBooking\Infrastructure\WordPress\Registry\CPTRegistry::getInstance();
    \TravelBooking\Infrastructure\WordPress\Registry\ACFRegistry::getInstance();
    \TravelBooking\Infrastructure\WordPress\Registry\TaxonomyRegistry::getInstance();
}

function tour_booking_system_create_table(): void {
    $database_installed = get_option('travel_database_installed');

    if (!$database_installed) {
        BookingIndexTable::getInstance();
        BookingMetaTable::getInstance();
        CustomerTable::getInstance();
        NotificationTable::getInstance();
        TourSchedulerTable::getInstance();
    }

    update_option('travel_database_installed', true);

}

// Bootstrap
tour_booking_system_register_wordpress_infrastructure();
tour_booking_system_create_table();

// Load Contact form 7 tag
\TravelBooking\Infrastructure\Integrations\CF7\RegistrarTagOptions::getInstance();


// Load Shortcode
\TravelBooking\Presentation\Shortcodes\SearchTourShortcode::getInstance();
\TravelBooking\Presentation\Shortcodes\AdvancedSearchTourRestShortcode::getInstance();

// Load Testing
require_once __DIR__ . "/tests/QueryTest.php";
require_once __DIR__ . "/tests/SearchTourRestApi.php";
