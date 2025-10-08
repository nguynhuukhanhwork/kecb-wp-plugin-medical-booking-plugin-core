<?php
/**
 * Repository class for handling CRUD operations on Booking table
 *
 * @package MedicalBooking\Infrastructure\Repository
 */

namespace MedicalBooking\Infrastructure\Repository;

use MedicalBooking\Infrastructure\DB\ConfigDb;
use MedicalBooking\Infrastructure\DB\BookingDb;
use WP_Error;
use wpdb;
use function MedicalBooking\Helpers\kecb_get_form_submission_cf7;

/**
 * Class BookingRepository
 *
 * Provides CRUD operations for bookings in the plugin's database.
 * Follows Repository pattern for data access layer.
 */
class BookingRepository
{
    /** @var string $table_booking Full table name for bookings */
    protected string $table_booking;

    /** @var wpdb $wpdb WordPress database object */
    protected wpdb $wpdb;

    /** @var ConfigDb $config Configuration instance */
    protected ConfigDb $config;

    protected int $form_id = 551;

    /**
     * BookingRepository constructor.
     *
     * Initializes the repository with WordPress DB and sets table names.
     */
    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->config = ConfigDb::getInstance();
        $this->table_booking = $this->config->getTableName('bookings');
        
        // Ensure table exists
        $this->ensureTableExists();
    }

    /**
     * Đảm bảo table tồn tại
     */
    private function ensureTableExists(): void
    {
        $bookingDb = BookingDb::getInstance();
        if (!$bookingDb->isTableReady()) {
            $bookingDb->createTable();
        }
    }

    /**
     * Thêm booking mới
     *
     * @param array $data Booking data
     * @return int|WP_Error Inserted booking ID on success, WP_Error on failure
     */
    public function addBooking(array $data): int|WP_Error
    {
        $allowed_fields = ['booking_type', 'doctor_id', 'user_name', 'user_email', 'user_phone', 'booking_data', 'status'];
        $filtered_data = array_intersect_key($data, array_flip($allowed_fields));
        
        // Set default values
        $filtered_data['status'] = $filtered_data['status'] ?? 'pending';
        $filtered_data['created_at'] = current_time('mysql');
        $filtered_data['updated_at'] = current_time('mysql');
        
        $result = $this->wpdb->insert(
            $this->table_booking,
            $filtered_data,
            $this->getFormatArray($filtered_data)
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

    /**
     * Lấy booking theo ID
     *
     * @param int $id Booking ID
     * @return array|object|null Booking data or null if not found
     */
    public function getBooking(int $id): array|object|null
    {
        $query = $this->wpdb->prepare(
            "SELECT * FROM {$this->table_booking} WHERE id = %d",
            $id
        );
        
        return $this->wpdb->get_row($query);
    }

    /**
     * Lấy tất cả bookings với pagination
     *
     * @param int $limit Số lượng records per page
     * @param int $offset Offset for pagination
     * @param string $status Filter by status (optional)
     * @return array List of bookings
     */
    public function getAllBookings(int $limit = 100, int $offset = 0, string $status = ''): array
    {
        $where_clause = '';
        $prepare_args = [$limit, $offset];
        
        if (!empty($status)) {
            $where_clause = ' WHERE status = %s';
            $prepare_args = [$status, $limit, $offset];
        }
        
        $query = $this->wpdb->prepare(
            "SELECT * FROM {$this->table_booking}{$where_clause} ORDER BY created_at DESC LIMIT %d OFFSET %d",
            ...$prepare_args
        );
        
        return $this->wpdb->get_results($query, ARRAY_A);
    }

    /**
     * Lấy bookings theo doctor ID
     *
     * @param int $doctor_id Doctor ID
     * @param int $limit Limit results
     * @param int $offset Offset for pagination
     * @return array List of bookings
     */
    public function getBookingsByDoctor(int $doctor_id, int $limit = 100, int $offset = 0): array
    {
        $query = $this->wpdb->prepare(
            "SELECT * FROM {$this->table_booking} WHERE doctor_id = %d ORDER BY created_at DESC LIMIT %d OFFSET %d",
            $doctor_id,
            $limit,
            $offset
        );
        
        return $this->wpdb->get_results($query, ARRAY_A);
    }

    /**
     * Cập nhật trạng thái booking
     *
     * @param int $id Booking ID
     * @param string $status New status
     * @return int|WP_Error Number of affected rows on success, WP_Error on failure
     */
    public function updateBookingStatus(int $id, string $status): int|WP_Error
    {
        $allowed_statuses = ['pending', 'confirmed', 'cancelled', 'completed'];
        
        if (!in_array($status, $allowed_statuses)) {
            return new WP_Error(
                'invalid_status',
                __('Trạng thái không hợp lệ', 'medical-booking-core')
            );
        }

        $result = $this->wpdb->update(
            $this->table_booking,
            [
                'status' => $status,
                'updated_at' => current_time('mysql')
            ],
            ['id' => $id],
            ['%s', '%s'],
            ['%d']
        );

        if ($result === false) {
            return new WP_Error(
                'db_update_error',
                __('Không thể cập nhật trạng thái booking', 'medical-booking-core'),
                $this->wpdb->last_error
            );
        }

        return $result;
    }

    /**
     * Cập nhật booking
     *
     * @param int $id Booking ID
     * @param array $data Data to update
     * @return int|WP_Error Number of affected rows on success, WP_Error on failure
     */
    public function updateBooking(int $id, array $data): int|WP_Error
    {
        $allowed_fields = ['booking_type', 'doctor_id', 'user_name', 'user_email', 'user_phone', 'booking_data', 'status'];
        $filtered_data = array_intersect_key($data, array_flip($allowed_fields));
        
        if (empty($filtered_data)) {
            return new WP_Error(
                'no_data_to_update',
                __('Không có dữ liệu để cập nhật', 'medical-booking-core')
            );
        }
        
        $filtered_data['updated_at'] = current_time('mysql');
        
        $result = $this->wpdb->update(
            $this->table_booking,
            $filtered_data,
            ['id' => $id],
            $this->getFormatArray($filtered_data),
            ['%d']
        );

        if ($result === false) {
            return new WP_Error(
                'db_update_error',
                __('Không thể cập nhật booking', 'medical-booking-core'),
                $this->wpdb->last_error
            );
        }

        return $result;
    }

    /**
     * Xóa booking
     *
     * @param int $id Booking ID
     * @return int|WP_Error Number of affected rows on success, WP_Error on failure
     */
    public function deleteBooking(int $id): int|WP_Error
    {
        $result = $this->wpdb->delete(
            $this->table_booking,
            ['id' => $id],
            ['%d']
        );

        if ($result === false) {
            return new WP_Error(
                'db_delete_error',
                __('Không thể xóa booking', 'medical-booking-core'),
                $this->wpdb->last_error
            );
        }

        return $result;
    }

    /**
     * Đếm số lượng bookings
     *
     * @param string $status Filter by status (optional)
     * @return int Number of bookings
     */
    public function countBookings(string $status = ''): int
    {
        if (!empty($status)) {
            $query = $this->wpdb->prepare(
                "SELECT COUNT(*) FROM {$this->table_booking} WHERE status = %s",
                $status
            );
        } else {
            $query = "SELECT COUNT(*) FROM {$this->table_booking}";
        }
        
        return (int) $this->wpdb->get_var($query);
    }

    /**
     * Lấy format array cho wpdb operations
     *
     * @param array $data Data array
     * @return array Format array
     */
    private function getFormatArray(array $data): array
    {
        $formats = [];
        foreach ($data as $value) {
            if (is_int($value)) {
                $formats[] = '%d';
            } elseif (is_float($value)) {
                $formats[] = '%f';
            } else {
                $formats[] = '%s';
            }
        }
        return $formats;
    }

    /**
     * Lấy thống kê bookings
     *
     * @return array Booking statistics
     */
    public function getBookingStats(): array
    {
        $stats = [];
        
        // Total bookings
        $stats['total'] = $this->countBookings();
        
        // By status
        $stats['by_status'] = [
            'pending' => $this->countBookings('pending'),
            'confirmed' => $this->countBookings('confirmed'),
            'cancelled' => $this->countBookings('cancelled'),
            'completed' => $this->countBookings('completed'),
        ];
        
        // Today's bookings
        $today = current_time('Y-m-d');
        $query = $this->wpdb->prepare(
            "SELECT COUNT(*) FROM {$this->table_booking} WHERE DATE(created_at) = %s",
            $today
        );
        $stats['today'] = (int) $this->wpdb->get_var($query);
        
        return $stats;
    }

    /**
     * Get data form Contact Form
     * @param int $limit
     * @return array
     */
    public function getBookingData(int $limit): array
    {
        $form_id = $this->form_id;
        return kecb_get_form_submission_cf7($form_id, $limit);
    }
}