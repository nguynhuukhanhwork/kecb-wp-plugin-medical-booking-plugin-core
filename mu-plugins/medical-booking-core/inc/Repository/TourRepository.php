<?php

namespace TravelBooking\Repository;

use TravelBooking\Infrastructure\WordPress\Registry\CPTRegistry;
use TravelBooking\Repository\BasePostTypeRepository;

final class TourRepository extends BasePostTypeRepository
{
    private static ?self $instance = null;
    private function __construct() {
        parent::__construct();
    }
    private function __clone()
    {
    }

    public function __wakeup()
    {
    }
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
    public function getAllNames(): array
    {
        return parent::getAllNames();
    }
    public function getTourTypeTermNames(): array
    {
        return parent::getTermList('tour_type');
    }
    public function geTourLocationTermNames(): array
    {
        return parent::getTermList('tour_location');
    }
    public function getTourCostTermNames(): array
    {
        return parent::getTermList('tour_cost');
    }
    public function getTourPersonTermNames(): array {
        return parent::getTermList('tour_person');
    }
    public function getTourLinkedTermNames(): array
    {
        return parent::getTermList('tour_linked');
    }
}