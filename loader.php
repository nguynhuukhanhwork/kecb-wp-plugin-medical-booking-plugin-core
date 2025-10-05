<?php

/** Autoload File */
require_once __DIR__.'/vendor/autoload.php';
/** Load Helper function */
require_once __DIR__.'/helpers.php';
/** Load Const */
require_once __DIR__.'/constant.php';

use MedicalBooking\Infrastructure\DB\DbBooking;
use MedicalBooking\Infrastructure\DB\DbInstaller;
use MedicalBooking\Infrastructure\DB\Taxonomy;
use MedicalBooking\Infrastructure\Repository\DoctorRepository;
use MedicalBooking\Infrastructure\Repository\ServiceRepository;
use MedicalBooking\Presentation\admin\AdminPage;
use MedicalBooking\Presentation\shortcodes\MB_Search_Form_Shortcode;

new ServiceRepository();
new Taxonomy();
new DbInstaller();
new \MedicalBooking\Infrastructure\Repository\DoctorRepository();
new \MedicalBooking\Infrastructure\Repository\PatientRepository();
new AdminPage();
DbBooking::getInstance();
new \MedicalBooking\Application\Service\PostSearchService();
MB_Search_Form_Shortcode::get_instance();
new DoctorRepository();