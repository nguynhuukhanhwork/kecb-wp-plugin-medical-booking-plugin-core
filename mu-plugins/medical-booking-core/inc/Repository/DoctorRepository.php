<?php

namespace MedicalBooking\Repository;

use WP_Query;

final class DoctorRepository extends BaseRepository
{
    private static ?self $instance = null;
    private string $post_type;
    private function __construct() {
        $this->post_type = 'doctor';
    }

    public static function get_instance(): self {
        return self::$instance ?? (self::$instance = new self());
    }

    public function get_by_id(int $doctor_id): object|false
    {
        $doctor = get_post($doctor_id);

        // Check Post type name
        if ($doctor->post_type !== $this->post_type) {
            kecb_error_log("Post type does not exist");
            return false;
        }

        // Check null
        if (empty($doctor)) {
            kecb_error_log("Doctor not found");
            return false;
        }

        return $doctor;
    }

    public function get_ids(): array
    {
        // Query
        $doctors = get_posts([
            'post_type' => $this->post_type,
            'posts_per_page' => -1,
            'orderby' => 'date',
            'order' => 'DESC',
            'fields' => 'ids',
        ]);

        return $doctors ?: [];
    }

    public function get_all(): object|false {

        $args = [
            'post_type'      => $this->post_type,
            'posts_per_page' => 5,
            'paged'          => 100,
            'meta_query'     => [
                [
                    'key'     => 'doctor_department',
                    'value'   => 'Cardiology',
                    'compare' => '=',
                ]
            ],
        ];

        $doctor = new WP_Query($args);
    }

}