<?php

/** Autoload File */
require_once __DIR__.'/vendor/autoload.php';
/** Load Helper function */
require_once __DIR__.'/helpers.php';
/** Load Const */
require_once __DIR__.'/constant.php';

use MedicalBooking\Infrastructure\DB\BookingDb;
use MedicalBooking\Infrastructure\DB\InstallDb;
use MedicalBooking\Infrastructure\DB\Taxonomy;
use MedicalBooking\Infrastructure\Repository\DoctorRepository;
use MedicalBooking\Infrastructure\Repository\ServiceRepository;
use MedicalBooking\Presentation\admin\AdminPage;
use MedicalBooking\Presentation\shortcodes\MB_Search_Form_Shortcode;

new ServiceRepository();
new Taxonomy();
new InstallDb();
new \MedicalBooking\Infrastructure\Repository\DoctorRepository();
new \MedicalBooking\Infrastructure\Repository\PatientRepository();
new AdminPage();
BookingDb::getInstance();
new \MedicalBooking\Application\Service\PostSearchService();
new \MedicalBooking\Application\Service\DoctorService();
MB_Search_Form_Shortcode::get_instance();