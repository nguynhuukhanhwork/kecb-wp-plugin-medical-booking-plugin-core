<?php

namespace TravelBooking\Repository;

use TravelBooking\Infrastructure\Database\BookingIndexTable;
use TravelBooking\Repository\BaseCustomTable;

final class BookingIndexRepository extends BaseCustomTable
{
    private static ?self $instance = null;
    private $table;
    private function __construct() {
        $this->table = BookingIndexTable::getInstance();
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