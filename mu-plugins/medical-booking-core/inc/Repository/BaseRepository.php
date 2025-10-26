<?php

namespace MedicalBooking\Repository;
use wpdb;
use WP_Query;
abstract class BaseRepository
{
    protected wpdb $wpdb;

    private function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
    }
}