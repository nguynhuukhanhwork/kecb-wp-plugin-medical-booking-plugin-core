<?php

namespace MedicalBooking\Infrastructure\Database;

final class BookingTable extends BaseTable
{
    protected string $table_name;
    private static ?self $instance = null;

    private function __construct()
    {
        $this->table_name = 'bookings';
        parent::__construct($this->table_name);
        add_action('init', [$this, 'create_table']);
    }

    public static function get_instance(): self
    {
        return self::$instance ??= new self();
    }

    protected function get_schema(string $table_name): string
    {
        return "
        CREATE TABLE IF NOT EXISTS $table_name (
            id BIGINT PRIMARY KEY,
            
            -- Booking data
            booking_code varchar(32) NOT NULL,
            booking_type varchar(127) NOT NULL DEFAULT 'null',
            booking_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            booking_status ENUM('pending', 'active', 'inactive') DEFAULT 'pending',
            
            -- Reference data
            customer_id BIGINT NOT NULL, -- Reference Table Customer
            service_id BIGINT NOT NULL, -- Reference CPT service
            doctor_id BIGINT NOT NULL -- Reference CPT doctor          
        ) $this->charset_collate;";
    }

    public function create_table(): void
    {
        require_once ABSPATH.'wp-admin/includes/upgrade.php';
        $sql = $this->get_schema($this->table_name).$this->charset_collate;
        dbDelta($sql);
    }
}
