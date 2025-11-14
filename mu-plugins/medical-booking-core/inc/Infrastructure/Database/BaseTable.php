<?php

namespace TravelBooking\Infrastructure\Database;

use TravelBooking\Infrastructure\Logger\Logger;
use wpdb;
use InvalidArgumentException;

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

    public function insertBaseRow(array $data) : false|int
    {
        $table = $this->getTableName();
        $inserted = $this->wpdb->insert($table, $data);

        if ($inserted === false) {
            Logger::log('Cannot insert data into ' . $table);
            return false;
        }

        return $this->wpdb->insert_id;
    }

    public function insertRow(array $data): int|false {
        $col_require = $this->validFormatData();

        // Check Key missing
        foreach ($col_require as $key) {
            if (!isset($data[$key])) {
                throw new InvalidArgumentException("Missing $key");
            }
        }

        // Filter key
        $insertData = array_intersect_key($data, array_flip($col_require));

        // Insert
        return $this->insertBaseRow($insertData);
    }

    abstract protected function validFormatData(): array;
    abstract protected static function ID_COLUMN_NAME(): string;
    // Basic method

    public function getRow(int $id): array|int {
        $id_name = static::ID_COLUMN_NAME();
        $table = $this->getTableName(); // Table name

        // SQL query
        $query = "SELECT * FROM $table WHERE $id_name = $id";
        $data = $this->wpdb->get_row($query, ARRAY_A);

        return $data ?? false;
    }

    public function deleteRow(int $id): int|false
    {
        $table = $this->getTableName();
        $id_name = static::ID_COLUMN_NAME();

        $deleted = $this->wpdb->delete(
            $table,
            [$id_name => $id],
            ['%d']
        );

        if ($deleted === false) {
            Logger::log('Cannot delete data from ' . $table);
            return false;
        }

        return $this->wpdb->insert_id;
    }
    abstract public function updateRow(int $id, array $data);

}