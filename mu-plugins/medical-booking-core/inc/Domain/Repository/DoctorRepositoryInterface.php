<?php

namespace MedicalBooking\Domain\Repository;

use MedicalBooking\Domain\Entity\Doctor;

interface DoctorRepositoryInterface
{
    public function getById(int $doctor_id): Doctor;
    public function getAllId(): array;
    public function searchByName(string $doctor_name): array;
    // public function exists(int $doctor_id): bool;
    // public function searchBySpeciality(string $doctor_speciality): array;
}