<?php

use MedicalBooking\Infrastructure\WordPress\Loader;

/** Autoload File */
require_once __DIR__.'/vendor/autoload.php';
/** Load Const */
require_once __DIR__.'/constant.php';

Loader::get_instance()->boot();
MedicalBooking\Infrastructure\Database\DatabaseLoader::get_instance()->boot();
