<?php
/**
 * Repository class for handling CRUD operations on Booking and Customer tables
 *
 * @package MedicalBookingCore\DB
 */

namespace MedicalBooking\Infrastructure\Repository;

use WP_Error;
use wpdb;

/**
 * Class BookingRepository
 *
 * Provides CRUD operations for customers and bookings in the plugin's database.
 */
class BookingRepository
{
    /** @var string $table_customer Full table name for customers */
    protected string $table_customer;

    /** @var string $table_booking Full table name for bookings */
    protected string $table_booking;

    /** @var wpdb $wpdb WordPress database object */
    protected wpdb $wpdb;

    /**
     * BookingRepository constructor.
     *
     * Initializes the repository with WordPress DB and sets table names.
     */
    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table_customer = $wpdb->prefix . 'mb_customers';
        $this->table_booking = $wpdb->prefix . 'mb_bookings';
    }

    /**
     * Add a new customer to the database.
     *
     * @noinspection PhpUnused
     *
     * @param string $name Full name of the customer
     * @param string $email Customer email
     * @param string $phone Customer phone number
     *
     * @return int|WP_Error Inserted customer ID on success, WP_Error on failure
     */
    public function addCustomer(string $name, string $email, string $phone): int|WP_Error
    {
        $result = $this->wpdb->insert(
            $this->table_customer,
            [
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
            ],
            ['%s', '%s', '%s']
        );

        if ($result === false) {
            return new WP_Error(
                'db_insert_error',
                __('Không thể thêm khách hàng', 'medical-booking-core'),
                $this->wpdb->last_error
            );
        }

        return $this->wpdb->insert_id;
    }

    /**
     * Add a new booking to the database.
     *
     * @noinspection PhpUnused
     *
     * @param int $customer_id ID of the customer
     * @param string $service Service name or ID
     * @param string $date Booking date (YYYY-MM-DD)
     * @param string $time_slot Time slot (HH:MM)
     * @param string $note Customer note
     *
     * @return int|WP_Error Inserted booking ID on success, WP_Error on failure
     */
    public function addBooking(
        int    $customer_id,
        string $service,
        string $date,
        string $time_slot,
        string $note
    ): int|WP_Error
    {
        $result = $this->wpdb->insert(
            $this->table_booking,
            [
                'customer_id' => $customer_id,
                'service' => $service,
                'date' => $date,
                'time_slot' => $time_slot,
                'note' => $note,
            ],
            ['%d', '%s', '%s', '%s', '%s']
        );

        if ($result === false) {
            return new WP_Error(
                'db_insert_error',
                __('Không thể thêm booking', 'medical-booking-core'),
                $this->wpdb->last_error
            );
        }
        return $this->wpdb->insert_id;
    }
}