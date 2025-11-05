<?php

namespace MedicalBooking\Repository;

use MedicalBooking\Infrastructure\Database\BaseTable;
use MedicalBooking\Infrastructure\Database\CustomerTable;

final class CustomerRepository extends BaseCustomTable
{
    private static ?self $instance = null;
    private function __construct() {
        parent::__construct(CustomerTable::getInstance());
    }
    public static function getInstance(): self
    {
        return self::$instance ?? self::$instance = new self();
    }
}