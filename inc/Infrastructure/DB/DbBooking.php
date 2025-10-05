<?php

namespace MedicalBooking\Infrastructure\DB;

use wpdb;

if (!defined('ABSPATH')) {
    exit;
}

final class DbBooking
{
    private static ?self $instance = null;
    private wpdb $wpdb;
    private string $table_name;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table_name = 'mbs_booking_data';
        $this->createTable();
    }

    public function createTable()
    {
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        $prefix = $this->wpdb->prefix;
        $charset_collate = $this->wpdb->get_charset_collate();

        $sql = "CREATE TABLE {$prefix}mbs_booking_data (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            booking_type VARCHAR(20),
            doctor_id INT,
            user_name VARCHAR(100) NOT NULL,
            user_email VARCHAR(100) NOT NULL,
            user_phone VARCHAR(100) NOT NULL,
            booking_data VARCHAR(500) NOT NULL
        ) $charset_collate;";

        dbDelta($sql);
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}
