<?php

use TravelBooking\Repository\TourRepository;

add_action('rest_api_init', function () {
    register_rest_route('travel-booking/v1', '/search-tours', [
        'methods'             => 'GET',
        'callback'            => 'tba_search_tours_api',
        'permission_callback' => '__return_true',
        'args'                => [
            'tour_type'     => ['sanitize_callback' => 'absint', 'default' => 0],
            'tour_location' => ['sanitize_callback' => 'absint', 'default' => 0],
            'tour_person'   => ['sanitize_callback' => 'absint', 'default' => 0],
            'tour_linked'   => ['sanitize_callback' => 'absint', 'default' => 0],
            'page'          => ['sanitize_callback' => 'absint', 'default' => 1],
            'per_page'      => ['sanitize_callback' => 'absint', 'default' => 12],
        ]
    ]);
});

function tba_search_tours_api($request) {
    $repo = TourRepository::getInstance();

    // 1. Lọc tour theo taxonomy
    $filtered_posts = $repo->filterAdvancedTour(
        $request['tour_type']     ?: null,
        $request['tour_location'] ?: null,
        $request['tour_linked']    ?: null,
        $request['tour_person']   ?: null
    );

    // 2. Build entity (array) từ WP_Post
    $entities = array_map([$repo, 'mapToEntity'], $filtered_posts);

    // 3. Phân trang
    $per_page = $request['per_page'];
    $page     = max(1, $request['page']);
    $offset   = ($page - 1) * $per_page;

    $paginated = array_slice($entities, $offset, $per_page);
    $total     = count($entities);
    $max_pages = max(1, ceil($total / $per_page));

    // 4. Response
    return rest_ensure_response([
        'success'     => true,
        'total'       => $total,
        'page'        => $page,
        'per_page'    => $per_page,
        'max_pages'   => $max_pages,
        'filters'     => array_filter([
            'tour_type'     => $request['tour_type'],
            'tour_location' => $request['tour_location'],
            'tour_person'   => $request['tour_person'],
            'tour_linked'   => $request['tour_linked'],
        ]),
        'tours'       => $paginated,
    ]);
}