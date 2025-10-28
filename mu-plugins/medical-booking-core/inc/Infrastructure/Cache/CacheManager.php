<?php

namespace MedicalBooking\Infrastructure\Cache;

final class CacheManager
{
    private static string $cache_key_prefix = 'medical_booking_';

    private function __construct() {}

    /**
     * Returns an array of cache expiration levels mapped to WordPress time constants.
     *
     * @return array<string, int> Cache levels with descriptive keys
     */
    public static function cache_level(): array
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
        return set_transient(self::$cache_key_prefix . $key, $value, 60);
    }

    public static function delete(string $key): bool
    {
        return delete_transient(self::$cache_key_prefix . $key . $key);
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