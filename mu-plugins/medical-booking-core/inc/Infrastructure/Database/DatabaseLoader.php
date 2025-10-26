<?php

namespace MedicalBooking\Infrastructure\Database;

final class DatabaseLoader
{
    public static ?self $instance = null;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    public function __wakeup()
    {
    }

    public static function get_instance(): self
    {
        return self::$instance ??= new self();
    }

    public function boot(): void
    {
        BookingTable::get_instance();
        PatientTable::get_instance();
    }
}
