<?php

namespace MedicalBooking\Repository;

abstract class BaseRepository
{
    protected \wpdb $wpdb;

    protected function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
    }

}