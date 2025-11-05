<?php

namespace MedicalBooking\Repository;

use MedicalBooking\Infrastructure\Database\NotificationTable;

final class NotificationRepository extends BaseCustomTable
{
    private static ?self $instance = null;
    protected $table;
    private function __construct() {
        $this->table = NotificationTable::getInstance();
        parent::__construct($this->table);
    }
    public static function getInstance(): self
    {
        return self::$instance ?? (self::$instance = new self());
    }

}