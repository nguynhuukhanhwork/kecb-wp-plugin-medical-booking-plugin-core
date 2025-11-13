<?php

namespace TravelBooking\Application\UseCase\ContactForm;

use TravelBooking\Infrastructure\Notification\TelegramNotification;
use TravelBooking\Repository\BookingDataRepository;
use TravelBooking\Repository\CustomerRepository;
use TravelBooking\Infrastructure\Logger\Logger;

abstract class BaseProcessContactForm
{
    protected $telegram_notification;
    protected $customer_repository;
    protected $booking_meta_repository;
    protected function __construct() {
        $this->telegram_notification = TelegramNotification::getInstance();
        $this->customer_repository = CustomerRepository::getInstance();
        $this->booking_meta_repository = BookingDataRepository::getInstance();
    }
    
    protected function sendNotification($message): bool {
        $result = $this->telegram_notification->send($message);

        if (!$result) {
            Logger::log("Telegram notification failed to send: " . $message);
            return false;
        }

        return true;
    }

    /**
     * Insert Customer data to Table
     * @param string $name
     * @param string $email
     * @param string $phone
     * @param string $message
     * @return bool
     */
    protected function saveCustomer(
        string $name,
        string $email,
        string $phone,
        string $message,
    ): bool {
        $result =  $this->customer_repository->add($name, $email, $phone, $message);
        if (!$result) {
            $user_info = "name: " . $name . ", email: " . $email . ", phone: " . $phone . ", message: " . $message;
            Logger::log("Cannot insert Customer " . $user_info);
            return false;
        }

        return true;
    }
    abstract function process(): bool;
}