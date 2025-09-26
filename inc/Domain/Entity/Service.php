<?php

namespace MedicalBooking\Domain\Entity;
use MedicalBooking\Base\MedicalBookingBase;
use function MedicalBooking\Helpers\kecb_write_error_log;

final class Service
{
    const post_type = 'service';

    // Fields of CPT
    const DESCRIPTION = 'service_description';
    const SHORT_DESCRIPTION = 'service_short_description';
    const DOCTOR_ID = 'service_doctor_id';
    const COST = 'service_cost';
    const TIME = 'service_time';

    // Const
    public function __construct() {

    }

    /**
     * Get shortcode description of Service Post Type
     */
    /** @noinspection PhpUnused */
    public function getShortDescriptionService(int $post_id): bool|string {
        return get_field(self::SHORT_DESCRIPTION, $post_id);
    }

    /**
     * Get value short description of Service Post Type
     * @param int $post_id is id of post type
     * @return bool|string
     */
    /** @noinspection PhpUnused */
    public function getDescriptionService(int $post_id): bool|string {
        return get_field(self::DESCRIPTION, $post_id);
    }

    /**
     * Get value time of Service Post Type
     * @param int $post_id
     * @return bool|string
     */
    /** @noinspection PhpUnused */
    public function getTimeService(int $post_id): bool|string {
        return get_field(self::TIME, $post_id);
    }

    /**
     * Get doctor ids list of Service Post Type
     * @param int $post_id
     * @return bool|array
     */
    /** @noinspection PhpUnused */
    public function getDoctorId(int $post_id): bool|array {
        return get_field(self::DOCTOR_ID, $post_id);
    }

    /**
     * Get value cost service of Service Post Type
     * @param int $post_id
     * @return bool|int
     */
    /** @noinspection PhpUnused */
    public function getCostService(int $post_id): bool|int {
        return get_field(self::COST, $post_id);
    }

    /**
     * Get speciality taxonomy terms of Service
     * @param int $post_id
     * @return array|false WP_Term[]|false
     */
    /** @noinspection PhpUnused */
    public function getSpecialityService(int $post_id): array|false {
        $terms = get_the_terms($post_id, Taxonomy::TAX_SPECIALITY);
        if (is_wp_error($terms) || empty($terms)) {
            kecb_write_error_log('False Post ID');
            return false;
        }
        return $terms;
    }
}