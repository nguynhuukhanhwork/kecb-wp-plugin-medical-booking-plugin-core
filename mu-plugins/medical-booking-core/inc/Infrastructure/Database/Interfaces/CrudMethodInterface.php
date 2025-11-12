<?php

namespace TravelBooking\Infrastructure\Database\Interfaces;

interface CrudMethodInterface
{
    /**
     * Insert a row and return the new ID
     */
    public function insertRow(array $data): int|false;
    public function updateRow(int $id, array $data): bool;
    public function deleteRow(int $id): bool;
}