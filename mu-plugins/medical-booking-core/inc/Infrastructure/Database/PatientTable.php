<?php

namespace MedicalBooking\Infrastructure\Database;

final class PatientTable extends BaseTable
{
    protected string $table_name;
    private static ?self $instance = null;

    private function __construct()
    {
        $this->table_name = 'patient';
        parent::__construct($this->table_name);
        add_action('init', [$this, 'create_table']);
    }

    public static function get_instance(): self
    {
        return self::$instance ??= new self();
    }

    public function get_schema(string $table_name = ''): string
    {
        $table = $table_name ?: $this->table_name;

        return "
        CREATE TABLE IF NOT EXISTS {$table} (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            
            -- Customer Information
            customer_name VARCHAR(30) NOT NULL DEFAULT '',
            customer_email VARCHAR(50) NOT NULL DEFAULT '',
            customer_phone VARCHAR(25) NOT NULL DEFAULT '',
            
            -- Scalability
            metadata JSON,
            
            -- Snapshot
            snapshot_customer_name VARCHAR(30) NOT NULL DEFAULT '',
            snapshot_customer_email VARCHAR(50) NOT NULL DEFAULT '',
            snapshot_customer_phone VARCHAR(25) NOT NULL DEFAULT '',
            
            -- Indexes
            UNIQUE KEY unique_email (customer_email),
            UNIQUE KEY unique_phone (customer_phone)
        ) {$this->charset_collate};";
    }

    public function create_table(): void
    {
        require_once ABSPATH.'wp-admin/includes/upgrade.php';
        $sql = $this->get_schema($this->table_name).$this->charset_collate;
        dbDelta($sql);
    }
}
