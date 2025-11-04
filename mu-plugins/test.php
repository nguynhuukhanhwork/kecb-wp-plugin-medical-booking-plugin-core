<?php
/*add_action('wp', function () {

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

    echo '</pre>';

    wp_reset_postdata();

});*/


/*add_action('init', function () {

    echo "<pre>";
    $data = \MedicalBooking\Repository\DoctorRepository::get_instance()->get_doctor_ids();
    echo "Số phần tử: " . count($data) . "\n\n";

    // Đo kích thước thực tế
    echo "strlen(serialize(\$data)):  " . strlen(serialize($data)) . " bytes\n";
    echo "strlen(json_encode(\$data)): " . strlen(json_encode($data)) . " bytes\n";

    // Đo bộ nhớ PHP cấp phát
    $before = memory_get_usage();
    $tmp = $data; // giữ array trong RAM
    $after = memory_get_usage();
    echo "\n memory_get_usage() difference: " . number_format($after - $before) . " bytes\n";

    // Đo tổng bộ nhớ toàn bộ script
    echo " Tổng bộ nhớ đang dùng: " . number_format(memory_get_usage(true)) . " bytes\n";

    echo "</pre>";
});*/

/*add_action('init', function () {
    $args = [
        'post_type'      => 'doctor',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'fields'         => 'ids',
        'no_found_rows'  => true,
        'update_post_meta_cache' => false,
        'update_post_term_cache' => false,
    ];
    $object_query = new \WP_Query($args);

    print_r($object_query->request);

});*/


add_action('init', function () {
    $tour_repo = \MedicalBooking\Repository\TourRepository::getInstance();
    $tours = $tour_repo->getAllEntities();
    foreach($tours as $tour) {
        foreach($tour as $data) {
            print_r($data);
        }
    }
});