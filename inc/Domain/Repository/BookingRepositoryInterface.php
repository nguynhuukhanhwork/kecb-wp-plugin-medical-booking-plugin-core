<?php

namespace MedicalBooking\Domain\Repository;


use MedicalBooking\Domain\Entity\Booking;

interface BookingRepositoryInterface
{
    public function getById(int $id): array;
    public function getAll(): array;
    public function deleteById(int $id): bool;
    public function create(Booking $booking): int;
    public function update(Booking $booking): int;
    public function save(Booking $booking): int;
}