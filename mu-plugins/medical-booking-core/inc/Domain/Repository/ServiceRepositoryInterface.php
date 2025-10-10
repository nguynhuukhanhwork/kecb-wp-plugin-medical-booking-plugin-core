<?php

namespace MedicalBooking\Domain\Repository;

use MedicalBooking\Domain\Entity\Doctor;

interface ServiceRepositoryInterface
{
    public function getById(int $doctor_id): Doctor;
    public function getAll(): array;
}