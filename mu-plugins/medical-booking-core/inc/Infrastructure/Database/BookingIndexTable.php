<?php

namespace TravelBooking\Infrastructure\Database;

use TravelBooking\Infrastructure\Database\BaseTable;

final class BookingIndexTable extends BaseTable
{
    public static ?self $instance = null;
    private function __construct(){
        parent::__construct();
    }
    private function __clone(){}
    public function __wakeup(){}
    public static function getInstance(): self
    {
        return self::$instance ??= (self::$instance = new self());
    }
    public static function TABLE_NAME(): string
    {
        return 'booking_index';
    }

    public function getTableName(): string
    {
        return parent::getTableName();
    }

    public function getSchema(): string
    {
        $table = $this->getTableName();
        $charset_collate = $this->getCharsetCollate();
        return "
        CREATE TABLE IF NOT EXISTS $table (
            booking_index_id BIGINT PRIMARY KEY AUTO_INCREMENT,
            
            -- Tour info
            tour_name VARCHAR(30) NOT NULL,
            tour_type VARCHAR(30) NOT NULL,
            tour_date_start DATE NOT NULL,
            tour_date_end DATE NOT NULL,
            tour_total_customer int(3) NOT NULL,
            
            -- Booking
            booking_date DATE NOT NULL,
            
            -- Customer info contact
            customer_name VARCHAR(50) NOT NULL,
            customer_email VARCHAR(50) NOT NULL,
            customer_phone VARCHAR(50) NOT NULL
        ) $charset_collate";
    }
    
    function getRow(int $id): array|false
    {
        $table = $this->getTableName();
        $query = "SELECT * FROM $table WHERE booking_index_id = $id";
        $data = $this->wpdb->get_row($query, ARRAY_A);
        return $data ?? false;
    }

    function deleteRow(int $id): bool
    {
        $table = $this->getTableName();
        $deleted = $this->wpdb->delete(
            $table,
            ['booking_index_id' => $id],
            ['%d']
        );

        return (bool) $deleted;
    }

    function updateRow(int $id, array $data): bool
    {
        $table = $this->getTableName();
        $updated = $this->wpdb->update(
            $table,
            $data,
            ['booking_index_id' => $id]
        );

        return (bool) $updated;
    }

    function insertRow(array $data): bool
    {
        $table = $this->getTableName();
        $created = $this->wpdb->insert($table, $data);
        return (bool) $created;
    }
}