<?php

namespace MedicalBooking\Repository;

use MedicalBooking\Infrastructure\WordPress\Registry\CPTRegistry;
use MedicalBooking\Repository\BasePostTypeRepository;

final class TourRepository extends BasePostTypeRepository
{
    private static ?self $instance = null;
    public static function getInstance(): self
    {
        return self::$instance ?? (self::$instance = new self());
    }

    static function DEFINE_CACHE_KEY_PREFIX(): string
    {
        return 'tour_';
    }

    static function POST_TYPE(): string
    {
        return CPTRegistry::getPostTypes('tour') ?? 'tour';
    }
    static function FIELDS(): array
    {
        return [
            'tour_code',
            'tour_featured_tour',
            'tour_duration_days',
            'tour_duration_nights',
            'tour_gallery'
        ];
    }
    static function TAXONOMY(): array
    {
        return [
            'tour_type',
            'tour_location',
            'tour_rating_level'
        ];
    }

    public function getAllIds(array $args = []): array
    {
        return parent::getAllIds($args);
    }
    public function getById(int $post_id): ?\WP_Post
    {
        return parent::getById($post_id);
    }
    public function getAllEntities(): array
    {
        return parent::getAllEntity();
    }
}