<?php
/**
 * PostSearchService class for MedicalBooking plugin.
 * Registers and handles a REST API endpoint for searching WordPress posts or custom post types.
 *
 * @since 1.0.0
 * @package MedicalBooking
 * @author KhanhECB
 */

namespace MedicalBooking\Application\Service;

if (!defined('ABSPATH')) exit;

class PostSearchService
{

    public function __construct()
    {
        // Đăng ký REST API endpoint
        add_action('rest_api_init', [$this, 'register_search_endpoint']);
    }

    /**
     * Đăng ký REST API endpoint cho search
     */
    public function register_search_endpoint()
    {
        register_rest_route('mb/v1', '/search', [
            'methods' => 'GET',
            'callback' => [$this, 'handle_search'],
            'permission_callback' => '__return_true',
            'args' => [
                'keyword' => [
                    'required' => false,
                    'default' => '',
                    'sanitize_callback' => 'sanitize_text_field'
                ],
                'post_type' => [
                    'required' => false,
                    'default' => 'post',
                    'sanitize_callback' => 'sanitize_text_field'
                ],
                'limit' => [
                    'required' => false,
                    'default' => 10,
                    'sanitize_callback' => 'absint'
                ],
                'page' => [
                    'required' => false,
                    'default' => 1,
                    'sanitize_callback' => 'absint'
                ]
            ]
        ]);
    }

    /**
     * Xử lý search request từ REST API
     * @param $request
     * @return mixed
     */
    public function handle_search($request): mixed
    {
        $keyword = $request->get_param('keyword');
        $post_type = $request->get_param('post_type');
        $limit = $request->get_param('limit');
        $page = $request->get_param('page');

        // Query arguments
        $args = [
            'post_type' => $post_type,
            'post_status' => 'publish',
            's' => $keyword,
            'posts_per_page' => $limit,
            'paged' => $page,
        ];

        $query = new \WP_Query($args);

        $results = [];
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $post_id = get_the_ID();

                $results[] = [
                    'id' => $post_id,
                    'title' => get_the_title(),
                    'link' => get_permalink(),
                    'excerpt' => wp_trim_words(get_the_content(), 20),
                    'thumbnail' => get_the_post_thumbnail_url($post_id, 'medium'),
                    'date' => get_the_date(),
                ];
            }
            wp_reset_postdata();
        }

        return rest_ensure_response([
            'success' => true,
            'total' => $query->found_posts,
            'pages' => $query->max_num_pages,
            'current_page' => $page,
            'results' => $results,
        ]);
    }
}

