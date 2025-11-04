<?php

namespace MedicalBooking\Infrastructure\Notification;

use MedicalBooking\Infrastructure\Database\NotificationTable;

abstract class BaseNotification
{
    protected $table;
    abstract public static function getType(): string;
    abstract public function send(string $message);
    protected function __construct() {
        $this->table = NotificationTable::getInstance();
    }

    public function insertSuccessNotification(string $message): bool {
        $status = 'success';
        $type = $this->getType();
        return $this->table->insertRow([
            'notification_type' => $type,
            'notification_message' => $message,
            'notification_status' => $status,
        ]);
    }

    public function insertErrorNotification(string $message, string $error): bool {
        $status = 'error';
        $type = $this->getType();
        return $this->table->insertRow([
            'notification_type' => $type,
            'notification_message' => $message,
            'notification_status' => $status,
            'notification_error_log' => $error,
        ]);
    }

}