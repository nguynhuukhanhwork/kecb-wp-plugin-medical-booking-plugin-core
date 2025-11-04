<?php

namespace MedicalBooking\Infrastructure\Database;

use wpdb;


abstract class BaseTable
{
    protected wpdb $wpdb;
    protected string $table_prefix;
    protected string $charset_collate;
    abstract protected static function TABLE_NAME(): string;
    abstract protected static function getInstance();

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

    public function getAll(int $limit = 30): array
    {
        $table = $this->getTableName();
        $sql = "SELECT * FROM $table LIMIT $limit";
        $results = $this->wpdb->get_results($sql);
        return $results ?? [];
    }

    // Basic method
    abstract public function getRow(int $id);
    abstract public function deleteRow(int $id);
    abstract public function updateRow(int $id, array $data);
    abstract public function insertRow(array $data);
}