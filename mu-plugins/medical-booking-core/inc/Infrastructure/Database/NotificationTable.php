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
    protected static function ID_COLUMN_NAME(): string
    {
        return 'notification_id';
    }
    protected function validFormatData(): array
    {
        return [
            'notification_type',
            'notification_message',
            'notification_status',
            'notification_error_log'
        ];
    }

    public function getSchema(): string
    {
        $table = $this->getTableName();
        $id_name = self::ID_COLUMN_NAME();
        $charset_collate = $this->getCharsetCollate();
        return "
        CREATE TABLE IF NOT EXISTS $table (
            -- id
            $id_name INT AUTO_INCREMENT PRIMARY KEY,
            
            -- main data
            notification_type VARCHAR(127) NOT NULL,
            notification_message TEXT NOT NULL,
            notification_status VARCHAR(20) NOT NULL DEFAULT 'error',
            notification_error_log TEXT DEFAULT NULL,
            
            -- date
            notification_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) $charset_collate;";
    }


    public function updateRow(int $id, array $data): bool
    {
       $table = $this->getTableName();
       return (bool) $this->wpdb->update($table, $data,[
           'notification_id' => $id
       ]);
    }
}