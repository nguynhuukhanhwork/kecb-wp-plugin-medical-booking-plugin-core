<?php

namespace TravelBooking\Presentation\Rest;

use TravelBooking\Repository\TourRepository;

final class TourEntitySearchController
{
    private static ?self $instance = null;
    private function __construct() {
        add_action('rest_api_init', [$this, 'init']);
    }
    public static function getInstance(): self
    {
        return self::$instance ?? (self::$instance = new self());
    }

    private function __clone() {}
    public function __wakeup() {
        throw new \Exception('Cannot unserialize a singleton.');
    }
    public function init(): void
    {
        register_rest_route('travel-booking/v1', '/search-tours', [
            'methods' => 'GET',
            'callback' => [$this, 'handleSearchTours'],
            'permission_callback' => '__return_true',
            'args' => [
                'type' => ['sanitize_callback' => 'absint', 'default' => 0],
                'location' => ['sanitize_callback' => 'absint', 'default' => 0],
                'person' => ['sanitize_callback' => 'absint', 'default' => 0],
                'linked' => ['sanitize_callback' => 'absint', 'default' => 0],
                'page' => ['sanitize_callback' => 'absint', 'default' => 1],
                'per_page' => ['sanitize_callback' => 'absint', 'default' => 12],
            ]
        ]);
    }

    public function handleSearchTours($request): \WP_Error|\WP_HTTP_Response|\WP_REST_Response
    {
        $repo = TourRepository::getInstance();

        // 1. Lọc tour theo taxonomy
        $filtered_posts = $repo->filterAdvancedTour(
            $request['type'] ?: null,
            $request['location'] ?: null,
            $request['linked'] ?: null,
            $request['person'] ?: null
        );

        // 2. Build entity (array) từ WP_Post
        $entities = array_map([$repo, 'mapToEntity'], $filtered_posts);

        // 3. Phân trang
        $per_page = $request['per_page'];
        $page = max(1, $request['page']);
        $offset = ($page - 1) * $per_page;

        $paginated = array_slice($entities, $offset, $per_page);
        $total = count($entities);
        $max_pages = max(1, ceil($total / $per_page));

        // 4. Response
        return rest_ensure_response([
            'success' => true,
            'total' => $total,
            'page' => $page,
            'per_page' => $per_page,
            'max_pages' => $max_pages,
            'filters' => array_filter([
                'type' => $request['type'],
                'location' => $request['location'],
                'person' => $request['person'],
                'linked' => $request['linked'],
            ]),
            'tours' => $paginated,
        ]);
    }


}