<?php

namespace MedicalBooking\Repository;

use MedicalBooking\Repository\BaseCustomTable;

class BookingRepository extends BaseCustomTable
{
    protected string $table_name = 'bookings';
    private string $cache_ket_prefix = 'booking_table_';
    protected int $cache_lifetime = WEEK_IN_SECONDS;

    static public ?self $instance = null;

    static public function getInstance(): self {
        return self::$instance ?? (self::$instance = new self());
    }

    public function getTableName(): string
    {
        return parent::getTableName();
    }

    public function getTablePrefix(): string {
        return parent::getTablePrefix();
    }

    public function getAllData(): array
    {
        return parent::getAllData();
    }


}