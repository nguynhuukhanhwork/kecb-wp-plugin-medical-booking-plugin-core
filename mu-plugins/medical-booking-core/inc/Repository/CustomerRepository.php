<?php

namespace TravelBooking\Repository;

use TravelBooking\Infrastructure\Database\BaseTable;
use TravelBooking\Infrastructure\Database\CustomerTable;

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
    public function add(string $name, string $email, string $phone, string $message = ''): bool {
        return parent::insertRow(
            [
                'customer_name' => $name,
                'customer_email' => $email,
                'customer_phone' => $phone,
                'customer_note' => $message
            ]
        );
    }
}
