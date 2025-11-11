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
    private function buildTaxQuery(
        ?int $type_id = null,
        ?int $loc_id = null,
        ?int $linked_id = null,
        ?int $person_id = null
    ): array {
        $relation = 'AND';
        $tax_query = ['relation' => $relation];

        $taxonomies = [
            'tour_type'     => $type_id,
            'tour_location' => $loc_id,
            'tour_linked'   => $linked_id,
            'tour_person'   => $person_id,
        ];

        foreach ($taxonomies as $taxonomy => $term_id) {
            if ($term_id > 0) {
                $tax_query[] = [
                    'taxonomy' => $taxonomy,
                    'field'    => 'term_id',
                    'terms'    => [$term_id],
                ];
            }
        }

        return ['tax_query' => $tax_query];
    }

    /**
     * Filter Tour Post Type Tour with all Taxonomy of Post Type Tour, return Post Type Object
     * @param int|null $type_id
     * @param int|null $loc_id
     * @param int|null $linked_id
     * @param int|null $person_id
     * @return array all Post Type Object
     */
    public function filterAdvancedTour(
        ?int $type_id = null,
        ?int $loc_id = null,
        ?int $linked_id = null,
        ?int $person_id = null
    ): array {
        $args = $this->buildTaxQuery($type_id, $loc_id, $linked_id, $person_id);

        $all_entity = parent::getAll($args);

        if (empty($all_entity)) {
            return [];
        }

        return $all_entity;
    }

    /**
     * Convert Post -> Array Entity
     * @param \WP_Post $post
     * @return array Array data chuẩn để Loop
     */
    public function mapToEntity(\WP_Post $post): array {
        return parent::mapToEntity($post);
    }
}