<?php

namespace MedicalBooking\Infrastructure\WordPress\Acf;

final class DoctorAcfRegistrar extends BaseAcfRegistrar
{
    private static ?self $instance = null;

    public static function get_instance(string $file_path): self
    {
        if (self::$instance === null) {
            self::$instance = new self($file_path);
        }
        return self::$instance;
    }
}