<?php

namespace TravelBooking\Infrastructure\Database;

final class BookingMetaTable extends BaseTable
{
    protected static ?self $instance = null;

    private function __construct()
    {
        parent::__construct();
    }

    private function __clone()
    {
    }

    public function __wakeup()
    {
    }

    public static function getInstance(): self
    {
        return self::$instance ??= (self::$instance = new self());
    }

    protected static function TABLE_NAME(): string
    {
        return 'booking_meta';
    }

    public function getSchema(): string
    {
        $table = $this->getTableName();
        $charset_collate = $this->getCharsetCollate();
        return "
        CREATE TABLE IF NOT EXISTS $table (
            booking_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            
            booking_code INT UNSIGNED NOT NULL,
            customer_id INT UNSIGNED NOT NULL,
            tour_id INT UNSIGNED NOT NULL,
            scheduler_id INT NOT NULL,
            
            booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            booking_status ENUM('pending','confirmed','cancelled'),
                                    
            -- Taxonomy
            taxonomy_tour_type_id BIGINT UNSIGNED NOT NULL,
            taxonomy_tour_location_id BIGINT UNSIGNED NOT NULL
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

        return (bool)$deleted;
    }

    public function updateRow(int $id, array $data): bool
    {
        $table = $this->getTableName();
        $updated = $this->wpdb->update(
            $table,
            $data,
            ['booking_id' => $id]
        );

        return (bool)$updated;
    }

    public function insertRow(array $data): bool
    {
        $table = $this->getTableName();
        $inserted = $this->wpdb->insert($table, $data);
        return (bool)$inserted;
    }
}