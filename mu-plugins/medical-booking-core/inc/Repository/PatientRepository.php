<?php

namespace MedicalBooking\Repository;

use MedicalBooking\Repository\BaseCustomTable;
final class PatientRepository extends BaseCustomTable
{
    public static ?self $instance = null;

    private function __construct() {
        parent::__construct();
    }

    private function __clone() {}
    private function __wakeup() {}
    public static function getInstance(): self
    {
        return self::$instance ?? (self::$instance = new self());
    }
    public function getTableName(): string
    {
        return parent::getTableName();
    }
    public function getTablePrefix(): string
    {
        return parent::getTablePrefix();
    }
    public function getById(int $id): array
    {
        return parent::getById($id);
    }
    public function getByEmail(string $email): array
    {
        if ($email) {
            return [];
        }

        $table = $this->getTableName();
        $sql = "SELECT * FROM {$table} WHERE email = '{$email}'";
        $data = $this->wpdb->get_results($sql);

        if (!empty($data)) {
            return $data;
        }

        return [];
    }

    public function getByPhone(string $phone): array
    {
        if (function_exists('kecb_validate_vietnam_phone'))
        {
            if (!kecb_validate_vietnam_phone($phone)) {
                return [];
            }
        }

        $table = $this->getTableName();
        $sql = "SELECT * FROM {$table} WHERE phone = '{$phone}'";
        $data = $this->wpdb->get_results($sql);
        if (!empty($data)) {
            return $data;
        }
        return [];
    }

    public function getAll(int $limit = 30): array
    {
        return parent::getAllData($limit);
    }
    public function getLastRows(int $limit = 30): array
    {
        return parent::getLastRows($limit);
    }

    public function toEntity(): array
    {
        return [];
        // TODO: Implement toEntity() method.
    }
}