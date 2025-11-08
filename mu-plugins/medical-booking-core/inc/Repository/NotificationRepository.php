<?php

namespace TravelBooking\Repository;

use TravelBooking\Infrastructure\Database\NotificationTable;

final class NotificationRepository extends BaseCustomTable
{
    private static ?self $instance = null;
    protected $table;
    private function __construct() {
        $this->table = NotificationTable::getInstance();
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