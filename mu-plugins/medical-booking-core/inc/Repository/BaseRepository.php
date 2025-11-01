<?php

namespace MedicalBooking\Repository;

use MedicalBooking\Infrastructure\Database\BaseTable;

abstract class BaseRepository
{
    private readonly BaseTable $table;
    public function __construct(BaseTable $table) {
        $this->table = $table;
    }
    public function getTableName(): string {
        return $this->table->getTableName();
    }

    public function insertRow(array $data): bool
    {
        return $this->table->insertRow($data);
    }
    public function updateRow(int $id, array $data): bool
    {
        return $this->table->updateRow($id, $data);
    }
    public function deleteRow(int $id): bool
    {
        return $this->table->deleteRow($id);
    }
    public function getRow(int $id): ?array
    {
        return $this->table->getRow($id);
    }
}