<?php

namespace TravelBooking\Repository;

use TravelBooking\Infrastructure\Database\BookingDataTable;

final class BookingDataRepository extends BaseCustomTable
{
    private static ?self $instance = null;
    private $table;

    private function __construct()
    {
        $this->table = BookingDataTable::getInstance();
        parent::__construct($this->table);
    }

    private function __clone()
    {
    }

    public function __wakeup()
    {
    }

    public static function getInstance(): self
    {
        return self::$instance ?? (self::$instance = new self());
    }

    public function addBooking(
        int    $customer_id,
        int    $tour_id,
        int    $scheduler_id,
        int    $taxonomy_tour_type_id,
        int    $taxonomy_tour_location_id,
        int    $taxonomy_tour_cost_id,
        int    $taxonomy_tour_linked_id,
        int    $taxonomy_tour_person_id,
        int    $taxonomy_tour_rating_id,
        string $booking_status = 'pending'
    ): int|false
    {
        $data = [
            $customer_id, $tour_id, $scheduler_id, $taxonomy_tour_type_id, $taxonomy_tour_location_id,
            $taxonomy_tour_cost_id, $taxonomy_tour_linked_id, $taxonomy_tour_person_id,
            $taxonomy_tour_rating_id, $booking_status
        ];

        $data = [
            'customer_id' => $customer_id,
            'tour_id' => $tour_id,
            'scheduler_id' => $scheduler_id,
            'taxonomy_tour_type_id' => $taxonomy_tour_type_id,
            'taxonomy_tour_location_id' => $taxonomy_tour_location_id,
            'taxonomy_tour_cost_id' => $taxonomy_tour_cost_id,
            'taxonomy_tour_linked_id' => $taxonomy_tour_linked_id,
            'taxonomy_tour_person_id' => $taxonomy_tour_person_id,
            'taxonomy_tour_rating_id' => $taxonomy_tour_rating_id,
            'booking_status' => $booking_status
        ];

        return $this->table->insertRow($data);
    }
}