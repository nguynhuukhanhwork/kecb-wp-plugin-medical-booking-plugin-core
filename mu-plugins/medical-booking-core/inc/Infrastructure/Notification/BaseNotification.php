<?php

namespace TravelBooking\Infrastructure\Notification;

use TravelBooking\Infrastructure\Database\NotificationTable;

abstract class BaseNotification
{
    protected $table;
    abstract protected function setup(): void;
    abstract public static function getType(): string;
    abstract public function send(string $message);
    protected function __construct() {
        $this->table = NotificationTable::getInstance();
    }

    protected function insertRow(array $data): bool|int
    {
        return $this->table->insertBaseRow($data);
    }
    protected function updateRow(int $id,array $data): bool
    {
        return $this->table->updateRow($id, $data);
    }
    protected function deleteRow(int $id): bool
    {
        return $this->table->deleteRow($id);
    }

    protected function insertSuccessNotification(string $message): bool {
        $status = 'success';
        $type = $this->getType();
        return $this->table->insertBaseRow([
            'notification_type' => $type,
            'notification_message' => $message,
            'notification_status' => $status,
        ]);
    }

    protected function insertErrorNotification(string $message, string $error): bool {
        $status = 'error';
        $type = $this->getType();
        return $this->table->insertBaseRow([
            'notification_type' => $type,
            'notification_message' => $message,
            'notification_status' => $status,
            'notification_error_log' => $error,
        ]);
    }

}