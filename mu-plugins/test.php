<?php
add_action('wp', function () {

    $cache_key = 'all_doctor_cache_key';

    $args = [
        'post_type' => 'doctor',
        'post_status' => 'publish',
        'posts_per_page' => 1,
        'orderby' => 'date',
        'order' => 'DESC'
    ];

    $query = new WP_Query($args);

    echo '<pre>';

    if ($query->have_posts()) {
        foreach ($query->posts as $post) {
            $fields = get_fields($post->ID);

            // Lấy terms taxonomy 'speciality'
            $doctor_special = wp_get_post_terms($post->ID, 'speciality', ["fields" => "names"]);
            $doctor_position = wp_get_post_terms($post->ID, 'position', ["fields" => "names"]);
            $doctor_gender  =  wp_get_post_terms($post->ID, 'gender', ["fields" => "names"]);
            $doctor_degree  =  wp_get_post_terms($post->ID, 'degree', ["fields" => "names"]);


            $data_format = [
                'name'          => get_the_title($post->ID),
                'image'         => get_the_post_thumbnail_url($post->ID, 'thumbnail'),
                'link'          => get_permalink($post->ID),
                'phone'         => $fields['doctor_phone'] ?? '',
                'email'         => $fields['doctor_email'] ?? '',
                'yoe'           => $fields['doctor_years_of_experience'] ?? 0,
                'schedule'      => $fields['doctor_schedule'] ?? '',
                'bio'           => $fields['doctor_bio'] ?? '',
                'degree'        => $doctor_degree ?? '',
                'speciality'    => $doctor_special ?? '',
                'position'      => $doctor_position ?? '',
                'gender'        => $doctor_gender ?? ''
            ];

            print_r($data_format);

            set_transient($cache_key, $data_format, 3600);
            echo "\n";
        }
    } else {
        echo "No doctors found.\n";
    }

    echo "\n---- SQL QUERY ----\n";
    var_dump($query->request);

    echo '</pre>';

    wp_reset_postdata();

});