<?php

namespace MedicalBooking\Infrastructure\Cache;

final class CacheManager
{
    private static string $cache_key_prefix = 'travel_booking_';
    private static string $option_database_cache_key = 'travel_booking_options';

    private function __construct() {}

    /**
     * Returns an array of cache expiration levels mapped to WordPress time constants.
     *
     * @return array<string, int> Cache levels with descriptive keys
     */
    public static function cacheLevel(): array
    {
        return [
            'minute' => MINUTE_IN_SECONDS, // 60 seconds
            'hour'   => HOUR_IN_SECONDS,   // 3600 seconds
            'day'    => DAY_IN_SECONDS,    // 86400 seconds
            'week'   => WEEK_IN_SECONDS,   // 604800 seconds
            'month'  => MONTH_IN_SECONDS,  // 2592000 seconds
        ];
    }

    public static function get(string $key, $default = false)
    {
        return get_transient(self::$cache_key_prefix . $key) ?: $default;
    }

    public static function set(string $key, $value, int $expiration = HOUR_IN_SECONDS): bool
    {
        $full_key = self::$cache_key_prefix . $key;

        // Load từ option
        $all_keys = get_option(self::$option_database_cache_key, []);

        // Thêm key nếu chưa có
        if (!in_array($full_key, $all_keys, true)) {
            $all_keys[] = $full_key;
            update_option(self::$option_database_cache_key, $all_keys);
        }

        // Set Cache
        return set_transient($full_key, $value, 60);
    }

    public static function delete(string $key): bool
    {
        return delete_transient(self::$cache_key_prefix . $key . $key);
    }

    public static function deleteAll(): bool
    {
        $cache_keys = self::allKeys();

        if (empty($cache_keys)) {
            error_log('No cache keys found');
            return false;
        }

        foreach ($cache_keys as $cache_key) {
            delete_transient($cache_key);
        }

        error_log('[Travel Booking] Cache is clear - ' . date('d.m.Y H:i:s'));
        return true;
    }

    public static function allKeys(): array
    {
        return get_option(self::$option_database_cache_key, []);
    }

    public static function generate_key(string $prefix, array $args = []): string
    {
        $key = $prefix;
        if (!empty($args)) {
            $query = http_build_query($args);
            $key .= '_' . md5($query);
        }
        return $key;
    }
}