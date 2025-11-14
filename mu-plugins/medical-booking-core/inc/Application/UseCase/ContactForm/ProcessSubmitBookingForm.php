<?php

namespace TravelBooking\Application\UseCase\ContactForm;

use TravelBooking\Infrastructure\Logger\Logger;
use WPCF7_Submission;
use Exception;
final class ProcessSubmitBookingForm extends BaseProcessContactForm
{
    private function __construct() {
        parent::__construct();
    }

    private static ?self $instance = null;
    public static function getInstance(): self
    {
        return self::$instance ?? (self::$instance = new self());
    }

    function process($data): bool
    {
        $submission = WPCF7_Submission::get_instance();

        $name =  $data['trbooking-customer-name'];
        $email = $data['trbooking-customer-email'];
        $phone = $data['trbooking-customer-phone'];

        // Booking data
        $tour_type = $data['trbooking-tour-type'];
        $tour_cost = $data['trbooking-tour-cost'];
        $tour_linked = $data['trbooking-tour-linked'];
        $tour_person = $data['trbooking-tour-person'];

        // Sanitize data
        $name = sanitize_text_field($name);
        $email = sanitize_email($email);
        $phone = sanitize_text_field($phone);
        $tour_type = $tour_type[0];
        $tour_cost = $tour_cost[0];
        $tour_linked = $tour_linked[0];
        $tour_person = $tour_person[0];

        $customer = $this->saveCustomer($name, $email, $phone);

        try {
            // Check data length
            if (!kecb_validate_vietnam_phone($phone)) {
                return throw new Exception('Số điện thoại không hợp lệ');
            }

            if (!is_email($email)) {
                return throw new Exception("Email is invalid");
            }

            if (strlen($phone) < 8 || strlen($phone) > 22) {
                return throw new Exception("Phone number is invalid");
            }
        }
        catch (Exception $e) {
            Logger::log($e->getMessage());
        }

        // message
        $contact_message     = "Tên - Email - Số điện thoại: $name - $email - $phone";
        $tour_type_message   = "Loại hình Tour: $tour_type";
        $tour_cost_message   = "Giá Tour: $tour_cost";
        $tour_linked_message = "Liên kết: $tour_linked";
        $tour_person_message = "Message: $tour_person";

        $message =
            "<pre><code>" .
            "Tên:</> $name\n" .
            "Email:</> $email\n" .
            "SĐT:</> $phone\n" .
            "Tour: $tour_type\n" .
            "Giá: $tour_cost" .
            "</code></pre>";

        // Booking data
        $notification = $this->sendNotification($message);

        return true;
    }
}
