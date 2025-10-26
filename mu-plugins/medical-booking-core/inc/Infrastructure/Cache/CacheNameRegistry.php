<?php

/**
 * Class Quản lý tên của Cache (prefix name)
 */
namespace MedicalBooking\Infrastructure\Cache;

final class CacheNameRegistry
{
    private static array $namespaces = [
        'doctor'   => 'doctor',
        'doctor_id' => 'doctor_id',
        'service'  => 'service',
        'service_id' => 'service_id',
        'taxonomy' => 'taxonomy',
        'option'   => 'option',
    ];

    public static function get(string $name): string
    {
        return self::$namespaces[$name] ?? 'default';
    }

    public static function all(): array
    {
        return self::$namespaces;
    }

    public static function exists(string $name): bool
    {
        return isset(self::$namespaces[$name]);
    }
}