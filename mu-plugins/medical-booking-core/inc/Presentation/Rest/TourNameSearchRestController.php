<?php

// inc/Presentation/Rest/TourSearchRestController.php
// http://localhost:3000/wp-json/travel-booking/v1/tours/search

namespace TravelBooking\Presentation\Rest;

use TravelBooking\Repository\TourRepository;
use WP_REST_Request;
use WP_REST_Response;

class TourNameSearchRestController {

    private TourRepository $repo;

    public function __construct() {
        $this->repo = TourRepository::getInstance();
        add_action( 'rest_api_init', [ $this, 'register_route' ] );
    }

    public function register_route(): void {
        register_rest_route(
            'travel-booking/v1',
            '/tours/search',
            [
                'methods'             => 'GET',
                'callback'            => [ $this, 'handle' ],
                'permission_callback' => '__return_true',
                'args'                => [
                    'q' => [
                        'default'           => '',
                        'sanitize_callback' => 'sanitize_text_field',
                    ],
                    'limit' => [
                        'default'           => 10,
                        'sanitize_callback' => 'absint',
                    ],
                ],
            ]
        );
    }

    public function handle( WP_REST_Request $request ): WP_REST_Response {
        $keyword = $request->get_param( 'keyword' );
        $limit   = min( (int) $request->get_param( 'limit' ), 50 );

        // Lấy danh sách tên
        $all = $this->repo->getPermalinkNameMap();

        // Lọc theo keyword
        $filtered = array_filter( $all, fn( $name ) => empty( $keyword ) || stripos( $name, $keyword ) !== false );

        $result = array_slice( array_values( $filtered ), 0, $limit );

        return new WP_REST_Response(
            [
                'success' => true,
                'data'    => $result,
                'total'   => count( $filtered ),
            ],
            200
        );
    }
}

new TourNameSearchRestController();