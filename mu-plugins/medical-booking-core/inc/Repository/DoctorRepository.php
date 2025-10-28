<?php

namespace MedicalBooking\Repository;

use MedicalBooking\Infrastructure\Cache\CacheManager;

final class DoctorRepository extends BasePostTypeRepository
{
    private static ?self $instance = null;
    private string $cache_key_prefix = 'doctor_';

    private function __construct() {
        parent::__construct('doctor');
    }

    private function __clone() {}
    public function __wakeup() {}

    public static function get_instance(): self {
        return self::$instance ?? (self::$instance = new self());
    }

    public function get_doctor_ids(): array
    {
        $cache_key = $this->cache_key_prefix . 'ids';

        $cached = CacheManager::get($cache_key);

        if ($cached) {
            return $cached;
        }

        $doctor_ids = $this->get_post_ids();
        CacheManager::set($cache_key, $doctor_ids, HOUR_IN_SECONDS);
        return $doctor_ids;
    }

    /**
     * Get all data form 1 doctor
     * @param int $doctor_id
     * @return array
     */
    public function get_doctor_by_id(int $doctor_id): array
    {
        $fields = get_fields($doctor_id);

        // Lấy terms taxonomy 'speciality'
        $doctor_special = wp_get_post_terms($doctor_id, 'speciality', ["fields" => "names"]);
        $doctor_position = wp_get_post_terms($doctor_id, 'position', ["fields" => "names"]);
        $doctor_gender  =  wp_get_post_terms($doctor_id, 'gender', ["fields" => "names"]);
        $doctor_degree  =  wp_get_post_terms($doctor_id, 'degree', ["fields" => "names"]);

        return [
            'name'          => get_the_title($doctor_id) ?? 'Bác sĩ',
            'image'         => get_the_post_thumbnail_url($doctor_id, 'thumbnail') ?? '',
            'link'          => get_permalink($doctor_id) ?? '',
            'phone'         => $fields['doctor_phone'] ?? '',
            'email'         => $fields['doctor_email'] ?? '',
            'yoe'           => $fields['doctor_years_of_experience'] ?? 0,
            'schedule'      => $fields['doctor_schedule'] ?? '',
            'bio'           => $fields['doctor_bio'] ?? '',
            'degree'        => $doctor_degree   ?? '',
            'speciality'    => $doctor_special  ?? '',
            'position'      => $doctor_position ?? '',
            'gender'        => $doctor_gender   ?? ''
        ];
    }

    public function get_all_doctor_data(): array {
        // Tạo khóa cache động dựa trên bộ lọc (nếu có)
        $cache_key = 'all_doctors';

        // Thử lấy dữ liệu từ cache
        $cached = CacheManager::get($cache_key);
        if (!empty($cached)) {
            return $cached;
        }

        $ids = $this->get_doctor_ids();
        if (empty($ids)) {
            return [];
        }

        $doctors = [];
        foreach ($ids as $id) {
            $doctor = $this->get_doctor_by_id($id);
            if ($doctor) {
                $doctors[] = $doctor;
            }
        }

        CacheManager::set($this->cache_key_prefix . 'all_doctors', $doctors, 3600);

        return $doctors;
    }
}