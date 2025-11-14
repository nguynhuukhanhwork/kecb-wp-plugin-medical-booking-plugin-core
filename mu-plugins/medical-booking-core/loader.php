<?php

use TravelBooking\Infrastructure\Database\BookingDataTable;
use TravelBooking\Infrastructure\Database\CustomerTable;
use TravelBooking\Infrastructure\Database\NotificationTable;
use TravelBooking\Infrastructure\Integrations\CF7\RegistrarTagOptions;
use TravelBooking\Infrastructure\WordPress\Registry\TaxonomyRegistry;
use TravelBooking\Repository\TourRepository;

/** Autoload File */
require_once __DIR__.'/vendor/autoload.php';
/** Load Const */
require_once __DIR__.'/constant.php';


// REST API Controller
\TravelBooking\Presentation\Rest\TourNameSearchRestController::getInstance();
\TravelBooking\Presentation\Rest\TourEntitySearchController::getInstance();

function tour_booking_system_register_wordpress_infrastructure(): void {
    \TravelBooking\Infrastructure\WordPress\Registry\CPTRegistry::getInstance();
    \TravelBooking\Infrastructure\WordPress\Registry\ACFRegistry::getInstance();
    \TravelBooking\Infrastructure\WordPress\Registry\TaxonomyRegistry::getInstance();
}

function tour_booking_system_create_table(): void {
    $option_name = \TravelBooking\Config\Enum\OptionName::DB_INSTALLED->value;
    $database_installed = get_option($option_name);

    if (!$database_installed) {
        // BookingIndexTable::getInstance();
        BookingDataTable::getInstance();
        CustomerTable::getInstance();
        NotificationTable::getInstance();
        //\TravelBooking\Infrastructure\Database\ContactDataTable::getInstance();
        // TourSchedulerTable::getInstance();

        update_option($option_name, true);
    }
}

// Bootstrap
tour_booking_system_register_wordpress_infrastructure();
tour_booking_system_create_table();


// Load Contact form 7 tag
\TravelBooking\Infrastructure\Integrations\CF7\RegistrarTagOptions::getInstance();

\TravelBooking\Infrastructure\Integrations\CF7\HandleFormSubmit::getInstance();

// Load Shortcode
\TravelBooking\Presentation\Shortcodes\SearchTourShortcode::getInstance();
\TravelBooking\Presentation\Shortcodes\AdvancedSearchTourRestShortcode::getInstance();

// Load Testing
require_once __DIR__ . "/tests/QueryTest.php";
// require_once __DIR__ . "/tests/SearchTourRestApi.php";
