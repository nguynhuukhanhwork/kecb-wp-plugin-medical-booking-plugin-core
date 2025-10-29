<?php

namespace MedicalBooking\Repository;

use MedicalBooking\Infrastructure\Cache\CacheManager;

final class DoctorRepository extends BasePostTypeRepository
{
    private static ?self $instance = null;
    protected string $cache_key_prefix = 'doctor_';
    protected array $taxonomies = [
        'speciality',
        'position',
        'gender',
        'degree'
    ];
    protected array $advanced_custom_fields = [
        'doctor_phone',
        'doctor_email',
        'doctor_years_of_experience',
        'doctor_schedule',
        'doctor_bio'
    ];
    protected int $cache_lifetime = WEEK_IN_SECONDS ?? 86400*7;
    private function __construct() {
        parent::__construct('doctor');
    }
    private function __clone() {}
    public function __wakeup() {}

    public static function get_instance(): self {
        return self::$instance ?? (self::$instance = new self());
    }

    public function getPostTypeName(): ?string
    {
        return parent::getPostTypeName() ?? 'doctor';
    }

    /**
     * gGet data format form 1 doctor
     * Data: name, thumbnail, permalink, phone, email, YOF, schedule, bio, degree, special, position, gender
     * @return array
     */
    public function format(\WP_POST $post): array
    {
        $terms = $this->getPostTermNames($post);

        $fields = get_fields($post->ID);

        return [
            'name'          => get_the_title($post->ID) ?? 'Bác sĩ',
            'image'         => get_the_post_thumbnail_url($post->ID, 'thumbnail') ?? '',
            'link'          => get_permalink($post->ID) ?? '',
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



}