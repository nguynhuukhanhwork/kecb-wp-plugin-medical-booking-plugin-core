<?php

namespace TravelBooking\Repository;

use TravelBooking\Infrastructure\Database\NotificationTable;

final class NotificationRepository extends BaseCustomTable
{
    private static ?self $instance = null;
    private function __construct() {
        parent::__construct(NotificationTable::getInstance());
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