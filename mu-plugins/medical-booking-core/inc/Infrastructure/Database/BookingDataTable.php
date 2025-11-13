<?php

namespace TravelBooking\Infrastructure\Database;

use Exception;

final class BookingDataTable extends BaseTable
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
        throw new Exception("Cannot unserialize a singleton.");
    }

    public static function getInstance(): self
    {
        return self::$instance ??= (self::$instance = new self());
    }

    protected static function ID_COLUMN_NAME(): string
    {
        return 'booking_id';
    }
    protected static function TABLE_NAME(): string
    {
        return 'booking_data';
    }

    public function getSchema(): string
    {
        $table = $this->getTableName();
        $id_name = self::ID_COLUMN_NAME();
        $charset_collate = $this->getCharsetCollate();
        return "
        CREATE TABLE IF NOT EXISTS $table (
            $id_name INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            
            booking_code CHAR(36) NOT NULL DEFAULT (UUID()),
            
            customer_id INT UNSIGNED NOT NULL,
            tour_id INT UNSIGNED NOT NULL,
            scheduler_id INT,
            
            booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            booking_status VARCHAR(20) NOT NULL DEFAULT 'pending',
                                    
            -- Taxonomy
            taxonomy_tour_type_id INT UNSIGNED NOT NULL,
            taxonomy_tour_location_id INT UNSIGNED NOT NULL,
            taxonomy_tour_cost_id INT UNSIGNED NOT NULL,
            taxonomy_tour_linked_id INT UNSIGNED NOT NULL,
            taxonomy_tour_person_id INT UNSIGNED NOT NULL,
            taxonomy_tour_rating_id INT UNSIGNED NOT NULL,
            
            -- index cho lọc nhanh
            KEY idx_customer (customer_id),
            KEY idx_tour (tour_id),
            KEY idx_status (booking_status)
        ) $charset_collate";
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

    protected function validFormatData(): array{
        return [
            'customer_id',
            'tour_id',
            'scheduler_id',
            'taxonomy_tour_type_id',
            'taxonomy_tour_location_id',
            'taxonomy_tour_cost_id',
            'taxonomy_tour_linked_id',
            'taxonomy_tour_person_id',
            'taxonomy_tour_rating_id',
            'booking_status'
        ];
    }

}


