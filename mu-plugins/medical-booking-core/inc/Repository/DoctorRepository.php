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
    private function __wakeup() {}

    public static function getInstance(): self {
        return self::$instance ?? (self::$instance = new self());
    }

    public function getPostTypeName(): ?string
    {
        return parent::getPostTypeName() ?? 'doctor';
    }

    /**
     * Convert post to entity array
     * @param \WP_Post $post
     * @return array
     */
    public function getEntity(\WP_Post $post): array
    {
        return parent::getEntity($post) ?? [];
    }

    public function getAllEntity(): array
    {
        return parent::getAllEntity();
    }

}