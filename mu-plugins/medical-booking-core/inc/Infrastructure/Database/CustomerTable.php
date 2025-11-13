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
    protected static function ID_COLUMN_NAME(): string
    {
        return 'id';
    }
    protected function validFormatData(): array
    {
        return [
            'customer_name',
            'customer_email',
            'customer_phone',
            'customer_note',
            'metadata'
        ];
    }

    public function getSchema(): string
    {
        $table = $this->getTableName();
        $id_name = self::ID_COLUMN_NAME();
        $charset_collate = $this->getCharsetCollate();

        return "
        CREATE TABLE IF NOT EXISTS $table (
            $id_name BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            
            -- Customer Information
            customer_name VARCHAR(30) NOT NULL DEFAULT '',
            customer_email VARCHAR(50) NOT NULL DEFAULT '',
            customer_phone VARCHAR(25) NOT NULL DEFAULT '',
            customer_note TEXT NOT NULL DEFAULT '',
            
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            
            -- Scalability
            metadata JSON
    
        ) $charset_collate;";

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

    public function insertBaseRow(array $data): int|false
    {
        return parent::insertBaseRow($data);
    }
}