<?php

namespace MedicalBooking\Infrastructure\Database;

use wpdb;

abstract class BaseTable
{
    protected wpdb $wpdb;
    protected string $table_prefix;
    protected string $charset_collate;
    abstract protected static function TABLE_NAME(): string;

    protected function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table_prefix = 'travel_booking_';
        add_action('init', [$this, 'createTable']);
    }

    abstract protected function getSchema(): string;
    protected function getTablePrefix(): string
    {
        return $this->wpdb->prefix . $this->table_prefix;
    }

    protected function getTableName(): string
    {
        $prefix = $this->getTablePrefix();
        return $prefix . static::TABLE_NAME();
    }

    protected function getCharsetCollate(): string
    {
        return $this->wpdb->get_charset_collate();
    }

    public function createTable(): void
    {
        define('tour_db_install', true);

        if (!tour_db_install) {
            return;
        }

        require_once ABSPATH.'wp-admin/includes/upgrade.php';
        $schema = $this->getSchema();
        dbDelta($schema);
    }

    public function getAll(): array
    {
        $table = $this->getTableName();
        $sql = 'SELECT * FROM ' . $table;
        $results = $this->wpdb->get_results($sql);
        return $results ?? [];
    }

    // Basic method
    abstract protected function getRow(int $id);
    abstract protected function deleteRow(int $id);
    abstract protected function updateRow(int $id, array $data);
    abstract protected function insertRow(array $data);
}