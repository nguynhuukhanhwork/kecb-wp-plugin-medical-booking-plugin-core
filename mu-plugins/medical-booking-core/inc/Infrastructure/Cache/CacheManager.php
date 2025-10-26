<?php

namespace MedicalBooking\Infrastructure\Cache;

class CacheManager
{
    public static function get(string $key, $default = false)
    {
        return get_transient($key) ?: $default;
    }

    public static function set(string $key, $value, int $expiration = WEEK_IN_SECONDS): bool
    {
        return set_transient($key, $value, $expiration);
    }

    public static function delete(string $key): bool
    {
        return delete_transient($key);
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