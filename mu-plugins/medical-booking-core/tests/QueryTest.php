<?php
add_action('init', function () {
    $args = [
        'post_type'      => 'doctor',
        'post_status'    => 'publish',
        'p'              => 460,
    ];

    $query = new WP_Query($args);

    echo '<pre>';
    print_r($query->posts);
    echo '</pre>';

    echo '<pre>';
    var_dump($query->request);
    echo '</pre>';
});


