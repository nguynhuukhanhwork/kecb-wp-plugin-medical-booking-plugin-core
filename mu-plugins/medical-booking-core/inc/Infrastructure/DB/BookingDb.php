<?php

namespace MedicalBooking\Infrastructure\DB;

if (!defined('ABSPATH')) {
    exit;
}

final class BookingDb extends BaseDB
{
    private static ?self $instance = null;

    public function __construct()
    {
        parent::__construct();
        $this->createTable();
    }

    private function __clone()
    {
    }

    public function __wakeup()
    {
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    protected function initializeTableName(): void
    {
        $this->table_name = $this->config->getTableName('bookings');
    }

    public function createTable(): void
    {
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        $charset_collate = $this->wpdb->get_charset_collate();

        $sql = "CREATE TABLE $this->table_name (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            booking_type VARCHAR(20),
            doctor_id INT,
            user_name VARCHAR(100) NOT NULL,
            user_email VARCHAR(100) NOT NULL,
            user_phone VARCHAR(100) NOT NULL,
            booking_data TEXT NOT NULL,
            status ENUM('pending', 'confirmed', 'cancelled', 'completed') DEFAULT 'pending',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) $charset_collate;";

        dbDelta($sql);
    }

    /**
     * Kiểm tra table có tồn tại và có structure đúng không
     */
    public function isTableReady(): bool
    {
        return $this->tableExists() && $this->count() >= 0;
    }

    /**
     * Lấy thông tin table structure
     */
    public function getTableStructure(): array
    {
        return $this->getTableInfo();
    }
}
