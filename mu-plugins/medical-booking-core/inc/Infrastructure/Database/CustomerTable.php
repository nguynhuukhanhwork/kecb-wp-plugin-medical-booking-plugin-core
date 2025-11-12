<?php

namespace TravelBooking\Infrastructure\Database;

final class CustomerTable extends BaseTable
{
    private static ?self $instance = null;
    private function __construct(){
        parent::__construct();
    }
    private function __clone(){}
    public function __wakeup(){}
    public static function getInstance(): self
    {
        return self::$instance ?? (self::$instance = new CustomerTable());
    }
    protected static function TABLE_NAME(): string
    {
        return 'customer';
    }
    public function getSchema(): string
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
            customer_note TEXT NOT NULL DEFAULT '',
            
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            
            -- Scalability
            metadata JSON,
    
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