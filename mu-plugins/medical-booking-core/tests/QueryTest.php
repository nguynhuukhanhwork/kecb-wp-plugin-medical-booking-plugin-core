<?php

add_action('init', function () {
    $option = \TravelBooking\Config\Enum\OptionName::DB_INSTALLED->value;
    update_option($option, false);
});