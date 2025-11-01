<?php

namespace MedicalBooking\Repository;

use MedicalBooking\Infrastructure\Database\CustomerTable;

class CustomerRepository extends BaseRepository
{
    public function __construct() {
        parent::__construct(CustomerTable::getInstance());
    }
}