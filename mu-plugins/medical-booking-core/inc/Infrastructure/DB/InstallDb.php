<?php

namespace MedicalBooking\Infrastructure\DB;

use wpdb;
use MedicalBooking\Infrastructure\DB\ConfigDb;
use function MedicalBooking\Helpers\kecb_write_error_log;

final class InstallDb
{
    private ConfigDb $config;

    /** @var array Table names mapping */
    private array $table_names;

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
        $this->config = ConfigDb::get_instance();
        $this->table_names = $this->config->getMainTableNames();
        $this->installIfNeeded();
    }

    /**
     * Install Need Table if table is not exist.
     */
    public function installIfNeeded(): bool
    {
        $is_installed = $this->isInstalled();
        // $this->install();
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
            MB_INFRASTRUCTURE_PATH . 'DB/schema/bookings.sql',
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
            '{{booking_table}}' => $this->table_names['bookings'],
            '{{charset_collate}}' => $this->wpdb->get_charset_collate(),
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $sql);
    }
}
