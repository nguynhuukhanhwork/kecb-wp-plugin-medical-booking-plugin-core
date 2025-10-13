<?php

namespace MedicalBooking\Infrastructure\Config;

/**
 * Trung tâm quản lý tất cả đường dẫn file config (CPT, ACF, Taxonomy)
 */
final class ConfigPaths {
    private const BASE_PATH = MB_INFRASTRUCTURE_PATH . 'Config';

    private static array $paths = [
        'cpt' => [
            'doctor'  => '/cpt-json/doctor-cpt.json',
            'patient' => '/cpt-json/patient-cpt.json',
            'service' => '/cpt-json/service-cpt.json',
        ],
        'acf' => [
            'doctor'  => '/acf-json/doctor-fields.json',
            'patient' => '/acf-json/patient-fields.json',
            'service' => '/acf-json/service-fields.json',
        ],
        'tax' => [
            'degree'       => '/tax-json/degree.json',
            'gender'       => '/tax-json/gender.json',
            'speciality'   => '/tax-json/speciality.json',
            'service_type' => '/tax-json/service-type.json',
            'position'     => '/tax-json/position.json',
        ],
    ];

    /**
     * Lấy đường dẫn tuyệt đối tới 1 file cấu hình cụ thể.
     */
    public static function get(string $group, string $key): ?string {
        if (!isset(self::$paths[$group][$key])) {
            return null;
        }

        return self::BASE_PATH . self::$paths[$group][$key];
    }

    /**
     * Lấy toàn bộ file trong 1 nhóm (vd: tất cả CPT, ACF, TAX)
     */
    public static function getGroup(string $group): ?array {
        if (!isset(self::$paths[$group])) {
            return null;
        }

        return array_map(
            fn($path) => self::BASE_PATH . $path,
            self::$paths[$group]
        );
    }

    /**
     * Lấy tất cả cấu hình.
     */
    public static function getAll(): array {
        $all = [];
        foreach (self::$paths as $group => $paths) {
            $all[$group] = array_map(
                fn($path) => self::BASE_PATH . $path,
                $paths
            );
        }
        return $all;
    }
}
