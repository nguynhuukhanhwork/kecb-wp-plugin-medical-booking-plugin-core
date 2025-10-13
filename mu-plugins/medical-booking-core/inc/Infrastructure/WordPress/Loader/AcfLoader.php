<?php

namespace MedicalBooking\Infrastructure\WordPress\Loader;

use MedicalBooking\Infrastructure\Config\ConfigPaths;
use MedicalBooking\Infrastructure\WordPress\Acf\DoctorAcfRegistrar;
use MedicalBooking\Infrastructure\WordPress\Acf\PatientAcfRegistrar;
use MedicalBooking\Infrastructure\WordPress\Acf\ServiceAcfRegistrar;


final class AcfLoader
{
    public static ?self $instance = null;

    private function __construct()
    {
        // Khởi tạo khi cần
        $this->loadAcf();
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
    private function loadAcf(): void
    {
        $config_acf = ConfigPaths::getGroup('acf');

        DoctorAcfRegistrar::get_instance($config_acf['doctor']);
        ServiceAcfRegistrar::get_instance($config_acf['service']);
        PatientAcfRegistrar::get_instance($config_acf['patient']);

    }
}