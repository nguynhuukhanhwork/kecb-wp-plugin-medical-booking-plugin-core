<?php

namespace MedicalBooking\Repository;

use MedicalBooking\Infrastructure\Cache\CacheManager;
use MedicalBooking\Repository\BaseCustomTable;

final class BookingRepository extends BaseCustomTable
{
    protected string $table_name = 'bookings';
    protected string $cache_key_prefix = 'booking_table_';
    protected int $cache_lifetime = WEEK_IN_SECONDS;

    static public ?self $instance = null;

    static public function getInstance(): self {
        return self::$instance ?? (self::$instance = new self());
    }

    public function getTablePrefix(): string {
        return parent::getTablePrefix();
    }

    public function getAll(int $limit = 30): array
    {
        return parent::getAllData($limit);
    }
    public function getById(int $id): array
    {
        return parent::getById($id);
    }
    public function getByIds(array $ids): array
    {
        return parent::getByIds($ids);
    }
    public function toEntity(): array
    {
        return [];
    }
    public function filterByStatus(string $status, int $limit = 30): array
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