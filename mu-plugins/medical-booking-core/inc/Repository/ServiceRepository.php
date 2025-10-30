<?php

namespace MedicalBooking\Repository;

use MedicalBooking\Infrastructure\Cache\CacheManager;

final class ServiceRepository extends BasePostTypeRepository
{
    private static ?self $instance = null;
    protected string $cache_key_prefix = 'service_';
    protected int $cache_lifetime = WEEK_IN_SECONDS ?? 86400*7;
    protected array $taxonomies = [
        'speciality', 'service_type'
    ];
    protected array $advanced_custom_fields = [
        'service_cost',
        'service_time',
        'service_short_description',
        'service_description',
        'service_doctor_id'
    ];

    private function __construct()
    {
        parent::__construct('service');
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getPostTypeName(): ?string
    {
        return parent::getPostTypeName() ?? 'service';
    }

    public function getAllService(): array
    {
        $ids = $this->getAllIds();

        if (empty($ids)) {
            return [];
        }

        $services = [];
        foreach ($ids as $id) {
            $post = $this->getById($id);
            $services[] = $this->toEntity($post);
        }

        return $services;
    }

    /**
     * Convert post to entity array
     * @param \WP_Post $post
     * @return array
     */
    public function toEntity(\WP_Post $post): array
    {
        return parent::toEntity($post);
    }
}