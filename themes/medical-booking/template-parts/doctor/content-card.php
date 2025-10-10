<?php

$query = get_posts([
   'post_type' => 'doctor',
   'post_status' => 'publish',
    'posts_per_page' => -1,
]);

foreach ($query as $doctor) {
    var_dump($doctor->post_title);
    echo "<hr>";
}