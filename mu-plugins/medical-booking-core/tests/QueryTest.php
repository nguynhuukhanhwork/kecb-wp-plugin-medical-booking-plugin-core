<?php

add_action('init', function () {
    $data = \TravelBooking\Repository\TourRepository::getInstance()->filterAdvancedTour(44);
    print_r($data);
});

