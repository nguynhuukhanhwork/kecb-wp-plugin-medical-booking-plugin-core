<?php

namespace MedicalBooking\Repository;

use MedicalBooking\Infrastructure\Cache\CacheManager;

abstract class BaseCustomTable
{
    protected string $table_name;
    private string $table_prefix = 'medical_booking_';
    protected string $cache_key_prefix;
    protected int $cache_lifetime;
    protected int $limit_data = 30;

    protected \wpdb $wpdb;

    abstract static public function getInstance();
    protected function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
    }

    public function getTableName(): string
    {
        return $this->wpdb->prefix . $this->table_prefix . $this->table_name;
    }

    protected function getTablePrefix(): string
    {
        return $this->wpdb->prefix . $this->table_prefix;
    }

    protected function getAllData(int $limit = 30): array
    {
        $cache_key = $this->cache_key_prefix . 'all_bookings';
        $cached = CacheManager::get($cache_key);

        if($cached) {
            return $cached;
        }

        $table = $this->getTableName();

        $data = $this->wpdb->get_results(
            "SELECT * FROM {$table} LIMIT $limit"
        );

        if (is_array($data)) {
            CacheManager::set($cache_key, $data, $this->cache_lifetime);
            return $data;
        }

        return [];
    }

    protected function getLastRows(int $limit = 30): array {
        $cache_key = $this->cache_key_prefix . 'last_bookings';

        $cached = CacheManager::get($cache_key);

        if($cached) {
            return $cached;
        }

        $table = $this->getTableName();
        $data = $this->wpdb->get_results("SELECT * FROM {$table} ORDER BY id DESC LIMIT $limit ");

        if (is_array($data)) {
            CacheManager::set($cache_key, $data, $this->cache_lifetime);
            return $data;
        }

        return [];
    }


    protected function getById(int $id): array
    {
        // Try get Cache
        $cache_key = $this->cache_key_prefix . 'by_id_' . $id;
        $cached = CacheManager::get($cache_key);
        if($cached) {
            return $cached;
        }

        // Query data
        $table = $this->getTableName();
        $sql = "SELECT * FROM {$table} WHERE id = {$id}";
        $rows = $this->wpdb->get_results($sql);

        // Check is array -> set Cache
        if (is_array($rows)) {
            CacheManager::set($cache_key, $rows, $this->cache_lifetime);
            return $rows;
        }

        return [];
    }

    protected function getByIds(array $ids): array {

        $cache_key = $this->cache_key_prefix . 'by_ids_' . md5(serialize($ids));
        $cached = CacheManager::get($cache_key);
        if($cached) {
            return $cached;
        }

        $results = [];
        foreach ($ids as $id) {
            if (is_int($id)) {
                $results[] = $this->getById($id);
            }
        }

        if (is_array($results)) {
            CacheManager::set($cache_key, $results, $this->cache_lifetime);
            return $results;
        }

        return [];
    }
    protected function getQuery(string $sql): mixed {

        if (empty($sql)) {
            error_log('getQuery() - SQL is empty');
            return null;
        }

        $data = $this->wpdb->get_results(
            $sql
        );

        if(empty($data)) {
            error_log('No results found for ' . $sql);
            return null;
        }

        return $data;
    }
    abstract public function toEntity(): array;
}