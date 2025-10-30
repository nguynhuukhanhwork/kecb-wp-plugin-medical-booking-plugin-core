<?php

namespace MedicalBooking\Repository;

use MedicalBooking\Infrastructure\Cache\CacheManager;

abstract class BaseCustomTable
{
    protected string $table_name;
    private string $table_prefix = 'medical_booking_';
    protected string $cache_key_prefix;
    protected int $cache_lifetime;

    protected \wpdb $wpdb;

    abstract static public function getInstance();
    protected function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
    }

    protected function getTableName(): string
    {
        return $this->wpdb->prefix . $this->table_prefix . $this->table_name;
    }


    protected function getTablePrefix(): string
    {
        return $this->wpdb->prefix . $this->table_prefix;
    }

    protected function getAllData(int $limit): array
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

    protected function getLastRows(int $limit): array {
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

    protected function filterByStatus(string $status, int $limit = 30): array
    {
        $cache_key = $this->cache_key_prefix . 'filter_by_booking_status_' . $status;
        $cached = CacheManager::get($cache_key);
        
        if($cached) {
            return $cached;
        }

        $allowed_statuses = [
            'pending',
            'activate',
            'deactivate',
        ];

        // Check allow status
        if (!in_array($status, $allowed_statuses)) {
            return [];
        }

        $table = $this->getTableName();
        $sql = "SELECT * FROM {$table} WHERE status = '{$status}' LIMIT {$limit}";

        $rows = $this->wpdb->get_results($sql);

        if (is_array($rows)) {
            return $rows;

        }
        return [];
    }

}