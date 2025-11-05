<?php

namespace MedicalBooking\Repository;

use MedicalBooking\Infrastructure\Database\BaseTable;

abstract class BaseCustomTable {
    private readonly BaseTable $table;
    public function __construct(BaseTable $table) {
        $this->table = $table;
    }
    abstract public static function getInstance();
    /**
     * Get All data from table
     * @param int $limit default get 30 rows
     * @return array
     */
    protected function getAll(int $limit = 30): array {
        return $this->table->getAll() ?? [];
    }

    protected function getByID(int $id): array {
        return $this->table->getRow($id) ?? [];
    }

    protected function deleteByID(int $id): bool {
        return $this->table->deleteRow($id) ?? false;
    }

    protected function insertRow(array $data): bool {
        return $this->table->insertRow($data) ?? false;
    }

    public function updateRow(int $id, array $data): bool {
        return $this->table->updateRow($id, $data);
    }

    protected function getTableName(): string {
        return $this->table->getTableName() ?? '';
    }
}