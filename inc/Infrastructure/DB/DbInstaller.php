<?php

namespace MedicalBooking\Infrastructure\DB;

use wpdb;
use function MedicalBooking\Helpers\kecb_write_error_log;

final class DbInstaller
{
    private DbConfig $config;

    /** @var array Table names mapping */
    private array $table_name;

    /** @var wpdb WordPress database instance */
    private wpdb $wpdb;

    public function __construct()
    {
        global $wpdb;
        if (!($wpdb instanceof wpdb)) {
            kecb_write_error_log('WordPress database object (wpdb) is not available.');

            return;
        }
        $this->wpdb = $wpdb;
        $this->config = DbConfig::getInstance();
        // $this->table_name = $this->config->getAllTables();
        $this->installIfNeeded();
    }

    /**
     * Install Need Table if table is not exist.
     */
    public function installIfNeeded(): bool
    {
        $is_installed = $this->isInstalled();
        if (!$is_installed) {
            $this->install();

            return true;
        }

        return false;
    }

    public function isInstalled(): bool
    {
        return (bool)get_option('mbs_db_installed', false);
    }

    /**
     * Install Database for Medical Booking System.
     */
    public function install(): void
    {
        if (!defined('MBS_CORE_INFRASTRUCTURE_PATH')) {
            kecb_write_error_log('Constant MBS_CORE_INFRASTRUCTURE_PATH is not defined.');
            return;
        }

        if (!defined('ABSPATH')) {
            kecb_write_error_log('ABSPATH is not defined. Ensure the script is running in a WordPress environment.');

            return;
        }

        $schema_files = [
            MB_INFRASTRUCTURE_PATH . 'DB/schema/appointments.sql',
            MB_INFRASTRUCTURE_PATH . 'DB/schema/customer.sql',
            MB_INFRASTRUCTURE_PATH . 'DB/schema/queue.sql',
        ];

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        foreach ($schema_files as $file) {
            if (!file_exists($file)) {
                kecb_write_error_log("SQL file missing: $file");
                continue;
            }

            $sql = file_get_contents($file);
            if ($sql === false) {
                kecb_write_error_log("Failed to read SQL file: $file");
                continue;
            }

            $sql = $this->replacePlaceholders($sql);

            // Execute table creation/update
            dbDelta($sql);
        }
        update_option('mbs_db_installed', true, false);
    }

    /**
     * Replace SQL Table Name for Medical Booking Table.
     */
    public function replacePlaceholders(string $sql): string
    {
        $replacements = [
            '{{customer_table}}' => $this->table_name['customers'],
            '{{appointment_table}}' => $this->table_name['appointments'],
            '{{queue_table}}' => $this->table_name['queue'],
            '{{charset_collate}}' => $this->wpdb->get_charset_collate(),
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $sql);
    }

    private function createTableBookingForm()
    {
        echo DbConfig::getTablePrefix();
    }
}
