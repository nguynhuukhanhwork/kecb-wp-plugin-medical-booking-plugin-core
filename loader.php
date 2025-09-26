<?php

require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/helpers.php';

use MedicalBooking\Infrastructure\DB\DbBooking;
use MedicalBooking\Infrastructure\DB\DbInstaller;
use MedicalBooking\Infrastructure\DB\Taxonomy;
use MedicalBooking\Infrastructure\Repository\ServiceRepository;
use MedicalBooking\Presentation\admin\AdminPage;
use MedicalBooking\Presentation\shortcodes;

new ServiceRepository();
new Taxonomy();
new DbInstaller();
new \MedicalBooking\Infrastructure\Repository\Doctor\DoctorRepository();
new \MedicalBooking\Infrastructure\Repository\PatientRepository();
new shortcodes\FormConsult();
new AdminPage();
DbBooking::getInstance();
