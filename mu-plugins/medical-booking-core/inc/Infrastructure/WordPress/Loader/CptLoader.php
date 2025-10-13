<?php

namespace MedicalBooking\Infrastructure\WordPress\Loader;

use MedicalBooking\Infrastructure\Config\ConfigPaths;
use MedicalBooking\Infrastructure\WordPress\Cpt\DoctorCptRegistrar;
use MedicalBooking\Infrastructure\WordPress\Cpt\PatientCptRegistrar;
use MedicalBooking\Infrastructure\WordPress\Cpt\ServiceCptRegistrar;

final class CptLoader
{
    public static ?self $instance = null;

    private function __construct()
    {
        // Khởi tạo khi cần
        $this->loadCpt();
    }

    public static function get_instance(): self
    {
        if (self::$instance === null) {
            return self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Hàm thực thi khi loader được gọi
     */
    private function loadCpt(): void
    {
        $config_cpt = ConfigPaths::getGroup('cpt');

        DoctorCptRegistrar::getInstance($config_cpt['doctor']);
        PatientCptRegistrar::getInstance($config_cpt['patient']);
        ServiceCptRegistrar::getInstance($config_cpt['service']);
    }
}