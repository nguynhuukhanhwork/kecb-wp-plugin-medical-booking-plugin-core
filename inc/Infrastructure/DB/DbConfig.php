<?php

namespace MedicalBooking\Infrastructure\DB;

use RuntimeException;
use wpdb;
use function MedicalBooking\Helpers\kecb_write_error_log;

final class DbConfig
{
    private static ?self $instance = null;
    private wpdb $wpdb;
    private array $table_name;

    private function __construct()
    {
        global $wpdb;
        if (!($wpdb instanceof wpdb)) {
            throw new RuntimeException('WordPress database object (wpdb) is not available.');
        }
        $this->wpdb = $wpdb;

        // $this->table_name = [
        //     'customers' => $this->getTablePrefix().'customers',
        //     'appointments' => $this->getTablePrefix().'appointments',
        //     'queue' => $this->getTablePrefix().'queue',
        // ];
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public static function getTablePrefix(): string
    {
        return self::$wpdb->prefix . 'mbs_';
    }

    public function getTableName(string $key): string
    {
        if (!isset($this->table_name[$key])) {
            kecb_write_error_log("Table name for key '$key' not found.");

            return '';
        }

        return $this->table_name[$key];
    }

    /**
     * Get All Table of Medical Booking System.
     *
     * @return array|string[]
     */
    public function getAllTables(): array
    {
        return $this->table_name;
    }
}
