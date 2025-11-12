<?php

namespace TravelBooking\Infrastructure\Database;

use TravelBooking\Infrastructure\Database\BaseTable;

final class TourSchedulerTable extends BaseTable
{
    public static ?self $instance = null;
    public static function getInstance(): self
    {
        return self::$instance ?? (self::$instance = new self());
    }
    protected static function TABLE_NAME(): string
    {
        return 'tour_scheduler';
    }

    public function getSchema(): string
    {
        $table = $this->getTableName();
        $charset_collate = $this->getCharsetCollate();
        return "
        CREATE TABLE IF NOT EXISTS $table (
            scheduler_id INT PRIMARY KEY,
            tour_id INT NOT NULL,
            start_date DATE NOT NULL,
            end_date DATE NOT NULL,
            price DECIMAL NOT NULL,
            seats_totol INT DEFAULT 0,
            seats_booked INT DEFAULT 0,
            status ENUM('open', 'closed', 'full') DEFAULT 'open',
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        ) $charset_collate;
        ";
    }

    public function getRow(int $id)
    {
        // TODO: Implement getRow() method.
    }

    public function deleteRow(int $id)
    {
        // TODO: Implement deleteRow() method.
    }

    public function updateRow(int $id, array $data)
    {
        // TODO: Implement updateRow() method.
    }

    public function insertRow(array $data): int|false
    {
        return parent::insertRow($data);
    }
}