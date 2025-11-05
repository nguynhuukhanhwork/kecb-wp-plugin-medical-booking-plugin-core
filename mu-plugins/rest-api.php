<?php

add_action('rest_api_init', function () {

    register_rest_route('tb/v1', '/service-search', [
            'methods' => 'GET',
            'callback' => function (\WP_REST_Request $request) {

                $keyword = sanitize_text_field($request->get_param('keyword') ?? '');
                $category = sanitize_text_field($request->get_param('category') ?? '');
                $limit = min(absint($request->get_param('limit') ?? 10), 50);
                $page = absint($request->get_param('page') ?? 1);

                $args = [
                        'post_type' => 'service',
                        's' => $keyword,
                        'posts_per_page' => $limit,
                        'paged' => $page,
                        'post_status' => 'publish',
                ];

                // Nếu filter theo taxonomy
                if ($category) {
                    $args['tax_query'] = [
                            [
                                    'taxonomy' => 'service_category',
                                    'field' => 'slug',
                                    'terms' => $category,
                            ]
                    ];
                }

                $query = new WP_Query($args);
                $results = [];

                if ($query->have_posts()) {
                    while ($query->have_posts()) {
                        $query->the_post();
                        $results[] = [
                                'id' => get_the_ID(),
                                'title' => get_the_title(),
                                'link' => get_permalink(),
                                'excerpt' => get_the_excerpt(),
                                'thumbnail' => get_the_post_thumbnail_url(get_the_ID(), 'medium'),
                        ];
                    }
                    wp_reset_postdata();
                }

                return [
                        'success' => true,
                        'total' => (int)$query->found_posts,
                        'page' => $page,
                        'limit' => $limit,
                        'data' => $results,
                ];
            },
            'permission_callback' => '__return_true',
            'args' => [
                    'keyword' => [
                            'required' => false,
                            'default' => '',
                            'sanitize_callback' => 'sanitize_text_field'
                    ],
                    'category' => [
                            'required' => false,
                            'default' => '',
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
});