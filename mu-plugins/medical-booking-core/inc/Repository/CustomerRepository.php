<?php

namespace MedicalBooking\Repository;

use MedicalBooking\Infrastructure\Database\CustomerTable;

class CustomerRepository extends BaseCustomTable
{
    public function __construct() {
        parent::__construct(CustomerTable::getInstance());
    }
}