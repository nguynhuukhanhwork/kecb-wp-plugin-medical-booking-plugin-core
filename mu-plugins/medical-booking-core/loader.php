<?php

use MedicalBooking\Infrastructure\Database\BookingIndexTable;
use MedicalBooking\Infrastructure\Database\BookingMetaTable;
use MedicalBooking\Infrastructure\Database\CustomerTable;
use MedicalBooking\Infrastructure\Database\NotificationTable;
use MedicalBooking\Infrastructure\Database\TourSchedulerTable;
use MedicalBooking\Infrastructure\WordPress\Registry\TaxonomyRegistry;

/** Autoload File */
require_once __DIR__.'/vendor/autoload.php';
/** Load Const */
require_once __DIR__.'/constant.php';

function tour_booking_system_register_wordpress_infrastructure() {
    \MedicalBooking\Infrastructure\WordPress\Registry\CPTRegistry::getInstance();
    \MedicalBooking\Infrastructure\WordPress\Registry\ACFRegistry::getInstance();
    \MedicalBooking\Infrastructure\WordPress\Registry\TaxonomyRegistry::getInstance();
}

function tour_booking_system_create_table(): void {
    BookingIndexTable::getInstance();
    BookingMetaTable::getInstance();
    CustomerTable::getInstance();
    NotificationTable::getInstance();
    TourSchedulerTable::getInstance();
}


// Bootstrap 
tour_booking_system_register_wordpress_infrastructure();
tour_booking_system_create_table();
