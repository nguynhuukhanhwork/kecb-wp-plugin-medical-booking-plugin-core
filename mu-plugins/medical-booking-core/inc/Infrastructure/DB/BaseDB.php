<?php

namespace MedicalBooking\Infrastructure\DB;

use wpdb;
use function MedicalBooking\Helpers\kecb_write_error_log;

/**
 * Base Database Class - Cung cấp pattern chung cho các DB class
 * 
 * @package MedicalBooking\Infrastructure\DB
 */
abstract class BaseDB
{
    protected wpdb $wpdb;
    protected ConfigDb $config;
    protected string $table_prefix;
    protected string $table_name;

    public function __construct()
    {
        global $wpdb;
        
        if (!($wpdb instanceof wpdb)) {
            kecb_write_error_log('WordPress database object (wpdb) is not available.');
            throw new \RuntimeException('WordPress database object (wpdb) is not available.');
        }

        $this->wpdb = $wpdb;
        $this->config = ConfigDb::get_instance();
        $this->table_prefix = $this->config->getTablePrefix();
        $this->initializeTableName();
    }

    /**
     * Khởi tạo tên table cho class con
     * Class con phải implement method này
     */
    abstract protected function initializeTableName(): void;

    /**
     * Lấy tên table đầy đủ
     */
    public function getTableName(): string
    {
        return $this->table_name;
    }

    /**
     * Lấy prefix table
     */
    public function getTablePrefix(): string
    {
        return $this->table_prefix;
    }

    /**
     * Kiểm tra table có tồn tại không
     */
    public function tableExists(): bool
    {
        $table_name = $this->getTableName();
        $query = $this->wpdb->prepare(
            "SHOW TABLES LIKE %s",
            $table_name
        );
        
        return $this->wpdb->get_var($query) === $table_name;
    }

    /**
     * Lấy thông tin table (columns, indexes, etc.)
     */
    public function getTableInfo(): array
    {
        if (!$this->tableExists()) {
            return [];
        }

        $table_name = $this->getTableName();
        return $this->wpdb->get_results("DESCRIBE {$table_name}", ARRAY_A);
    }

    /**
     * Đếm số records trong table
     */
    public function count(): int
    {
        if (!$this->tableExists()) {
            return 0;
        }

        $table_name = $this->getTableName();
        return (int) $this->wpdb->get_var("SELECT COUNT(*) FROM {$table_name}");
    }

    /**
     * Escape string cho SQL
     */
    protected function escape(string $string): string
    {
        return $this->wpdb->_escape($string);
    }

    /**
     * Prepare SQL query
     */
    protected function prepare(string $query, ...$args): string
    {
        return $this->wpdb->prepare($query, ...$args);
    }

    /**
     * Execute SQL query
     */
    protected function query(string $query): int|false
    {
        return $this->wpdb->query($query);
    }

    /**
     * Get single result
     */
    protected function getVar(string $query, int $x = 0, int $y = 0): string|null
    {
        return $this->wpdb->get_var($query, $x, $y);
    }

    /**
     * Get single row
     */
    protected function getRow(string $query, string $output = OBJECT, int $y = 0): array|object|null
    {
        return $this->wpdb->get_row($query, $output, $y);
    }

    /**
     * Get multiple rows
     */
    protected function getResults(string $query, string $output = OBJECT): array
    {
        return $this->wpdb->get_results($query, $output);
    }

    /**
     * Insert data
     */
    protected function insert(string $table, array $data, array|string $format = null): int|false
    {
        return $this->wpdb->insert($table, $data, $format);
    }

    /**
     * Update data
     */
    protected function update(string $table, array $data, array $where, array|string $format = null, array|string $whereFormat = null): int|false
    {
        return $this->wpdb->update($table, $data, $where, $format, $whereFormat);
    }

    /**
     * Delete data
     */
    protected function delete(string $table, array $where, array|string $whereFormat = null): int|false
    {
        return $this->wpdb->delete($table, $where, $whereFormat);
    }

    /**
     * Get last error
     */
    public function getLastError(): string
    {
        return $this->wpdb->last_error;
    }

    /**
     * Get last query
     */
    public function getLastQuery(): string
    {
        return $this->wpdb->last_query;
    }
}
