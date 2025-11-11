<?php

namespace TravelBooking\Application\UseCase\ContactForm;

use TravelBooking\Infrastructure\Notification\TelegramNotification;
use TravelBooking\Repository\BookingMetaRepository;
use TravelBooking\Repository\CustomerRepository;

abstract class BaseProcessContactForm
{
    protected $telegram_notification;
    protected $customer_repository;
    protected $booking_meta_repository;
    protected function __construct() {
        $this->telegram_notification = TelegramNotification::getInstance();
        $this->customer_repository = CustomerRepository::getInstance();
        $this->booking_meta_repository = BookingMetaRepository::getInstance();
    }
    abstract function process();
}