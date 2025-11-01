<?php

namespace MedicalBooking\Infrastructure\Database;

class CustomerTable extends BaseTable
{
    private static ?self $instance = null;
    private function __construct(){
        parent::__construct();
    }
    private function __clone(){}
    private function __wakeup(){}
    public static function getInstance(): self
    {
        return self::$instance ?? (self::$instance = new CustomerTable());
    }
    protected static function TABLE_NAME(): string
    {
        return 'customer';
    }

    protected function getSchema(): string
    {
        $table = $this->getTableName();
        $charset_collate = $this->getCharsetCollate();

        return "
        CREATE TABLE IF NOT EXISTS $table (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            
            -- Customer Information
            customer_name VARCHAR(30) NOT NULL DEFAULT '',
            customer_email VARCHAR(50) NOT NULL DEFAULT '',
            customer_phone VARCHAR(25) NOT NULL DEFAULT '',
            
            -- Scalability
            metadata JSON,
            
            -- Snapshot
            snapshot_customer_name VARCHAR(30) NOT NULL DEFAULT '',
            snapshot_customer_email VARCHAR(50) NOT NULL DEFAULT '',
            snapshot_customer_phone VARCHAR(25) NOT NULL DEFAULT '',
            
            -- Indexes
            UNIQUE KEY unique_email (customer_email),
            UNIQUE KEY unique_phone (customer_phone)
        ) $charset_collate;";

    }

    public function getRow(int $id): array|bool
    {
        $table = $this->getTableName();
        $query = "SELECT * FROM $table WHERE id = $id";
        $data = $this->wpdb->get_row($query, ARRAY_A);
        return $data ?? false;
    }

    public function deleteRow(int $id): bool
    {
        $table = $this->getTableName();
        $deleted = $this->wpdb->delete(
            $table,
            ['id' => $id],
            ['%d']
        );

        return (bool) $deleted;
    }

    public function updateRow(int $id, array $data): bool
    {
        $table = $this->getTableName();
        $updated = $this->wpdb->update(
            $table,
            $data,
            ['id' => $id]
        );

        return (bool) $updated;
    }

    public function insertRow(array $data): bool
    {
        $table = $this->getTableName();
        $inserted = $this->wpdb->insert($table, $data);
        return (bool) $inserted;
    }
}