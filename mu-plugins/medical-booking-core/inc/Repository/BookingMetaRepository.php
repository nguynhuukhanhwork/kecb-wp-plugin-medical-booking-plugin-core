<?php

namespace MedicalBooking\Repository;

use MedicalBooking\Infrastructure\Database\BookingMetaTable;

final class BookingMetaRepository extends BaseCustomTable
{
    private static ?self $instance = null;
    private $table;

    private function __construct()
    {
        $this->table = BookingMetaTable::getInstance();
        parent::__construct($this->table);
    }

    private function __clone()
    {
    }

    public function __wakeup()
    {
    }

    public static function getInstance(): self
    {
        return self::$instance ?? (self::$instance = new self());
    }

}