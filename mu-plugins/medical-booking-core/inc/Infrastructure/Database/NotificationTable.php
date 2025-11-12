<?php

namespace TravelBooking\Infrastructure\Database;

use TravelBooking\Infrastructure\Notification\BaseNotification;

final class NotificationTable extends BaseTable
{
    public static ?self $instance = null;
    public static function getInstance(): self
    {
        return self::$instance ?? (self::$instance = new self());
    }
    private function __clone(){}
    public function __wakeup(){}
    protected static function TABLE_NAME(): string
    {
        return 'notifications';
    }

    public function getSchema(): string
    {
        $table = $this->getTableName();
        $charset_collate = $this->getCharsetCollate();
        return "
        CREATE TABLE IF NOT EXISTS $table (
            notification_id INT AUTO_INCREMENT PRIMARY KEY,
            notification_type VARCHAR(127) NOT NULL,
            notification_message TEXT NOT NULL,
            notification_status ENUM('error', 'success') NOT NULL DEFAULT 'error',
            notification_error_log TEXT DEFAULT NULL,
            notification_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) $charset_collate;";
    }

    public function getRow(int $id): array|false
    {
        $table = $this->getTableName();
        $query = "SELECT * FROM `$table` WHERE `notification_id` = $id";
        $result = $this->wpdb->get_row($query, ARRAY_A);
        return is_array($result) ? $result : false;
    }

    public function deleteRow(int $id): bool
    {
        $table = $this->getTableName();
        return (bool) $this->wpdb->delete($table,[
            'notification_id' => $id
        ]);
    }

    public function updateRow(int $id, array $data): bool
    {
       $table = $this->getTableName();
       return (bool) $this->wpdb->update($table, $data,[
           'notification_id' => $id
       ]);
    }

    public function insertRow(array $data): int|false
    {
        return parent::insertRow($data);
    }

}