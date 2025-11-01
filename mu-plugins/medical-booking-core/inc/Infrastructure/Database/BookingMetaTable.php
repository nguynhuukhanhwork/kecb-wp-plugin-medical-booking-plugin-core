<?php

namespace MedicalBooking\Infrastructure\Database;

use MedicalBooking\Infrastructure\Database\BaseTable;

class BookingMetaTable extends BaseTable
{
    protected static ?self $instance = null;
    private function __construct(){
        parent::__construct();
    }
    private function __clone(){}
    private function __wakeup(){}
    public static function getInstance(): self
    {
        return self::$instance ??= (self::$instance = new self());
    }
    protected static function TABLE_NAME(): string
    {
        return 'booking_meta';
    }

    protected function getSchema(): string
    {
        $table = $this->getTableName();
        $charset_collate = $this->getCharsetCollate();
        return "
        CREATE TABLE IF NOT EXISTS $table (
            booking_id BIGINT PRIMARY KEY AUTO_INCREMENT,
            
            -- Booking data
            booking_code varchar(32) NOT NULL,
            booking_type varchar(127) NOT NULL DEFAULT 'null',
            booking_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            booking_status ENUM('pending', 'active', 'inactive') DEFAULT 'pending',
            
            -- Reference data
            customer_id BIGINT NOT NULL, -- Reference Table Customer
            service_id BIGINT NOT NULL, -- Reference CPT service
            doctor_id BIGINT NOT NULL -- Reference CPT doctor          
        ) $charset_collate";
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
            ['booking_id' => $id],
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
            ['booking_id' => $id]
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