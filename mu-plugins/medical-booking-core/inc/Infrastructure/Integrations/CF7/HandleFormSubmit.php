<?php

namespace TravelBooking\Infrastructure\Integrations\CF7;
use WPCF7_Submission;

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
        throw new \Exception("Cannot unserialize a singleton.");
    }

    public function handle(\WPCF7_ContactForm $contact_form) {
        if ($contact_form->id() !== 561) {
            return;
        }

        $submission = WPCF7_Submission::get_instance();
        if (!$submission) {
            return;
        }

        error_log("[CF7 Handler] Form 551 submitted at " . current_time('mysql'));
    }
}