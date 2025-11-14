<?php

namespace TravelBooking\Infrastructure\Integrations\CF7;
use TravelBooking\Application\UseCase\ContactForm\ProcessSubmitBookingForm;
use WPCF7_Submission;
use Exception;
class HandleFormSubmit
{
    private static ?self $instance = null;
    public static function getInstance(): self
    {
        return self::$instance ?? (self::$instance = new self());
    }
    private function __construct() {
        add_action('wpcf7_before_send_mail', [$this, 'handle']);
    }

    private function __clone() {}
    public function __wakeup() {
        throw new Exception("Cannot unserialize a singleton.");
    }

    public function handle(\WPCF7_ContactForm $contact_form) {
        // Chỉ xử lý form ID 551
        if ($contact_form->id() !== 551) return;

        $submission = WPCF7_Submission::get_instance();
        if (!$submission) return;

        $data = $submission->get_posted_data();

        $process = false;

        // Chỉ xử lý bước cuối
        if (!empty($data['final']) && $data['final'] == '1') {
            $data = $submission->get_posted_data();
            ProcessSubmitBookingForm::getInstance()->process($data);
        }
    }
}



