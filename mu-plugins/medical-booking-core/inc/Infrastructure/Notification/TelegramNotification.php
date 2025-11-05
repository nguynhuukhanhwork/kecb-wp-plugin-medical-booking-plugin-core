<?php

namespace MedicalBooking\Infrastructure\Notification;

use MedicalBooking\Infrastructure\Notification\BaseNotification;

final class TelegramNotification extends BaseNotification
{
    private string $token_api;
    private string $chat_id;
    private string $url;
    private static ?self $instance = null;

    public static function getInstance(): self
    {
        return self::$instance ?? (self::$instance = new self());
    }
    private function __construct()
    {
        parent::__construct();
        add_action('init', [$this, 'setup']);

    }

    protected function setup(): void
    {
        $this->token_api = TELEGRAM_API_TOKEN ?? '';
        $this->chat_id = TELEGRAM_CHAT_ID ?? '';
        $this->url = "https://api.telegram.org/bot$this->token_api/sendMessage";
    }

    public static function getType(): string
    {
        return 'Telegram';
    }

    public function send(string $message): bool
    {
        // Get URL and Chat ID Telegram
        $url = $this->url;
        $chat_id = $this->chat_id;

        // Check Empty Message
        if (empty($message)) {
            error_log("Telegram notification message can't be empty");
            return false;
        }

        // Sanitize data
        $message = sanitize_text_field($message);

        // Send data
        $response = wp_remote_post($url, [
            'body' => [
                'chat_id' => $chat_id,
                'text' => $message,
                'parse_mode' => 'HTML'
            ]
        ]);

        // Check error response and write to database
        if (is_wp_error($response)) {
            $error = $response->get_error_message();
            $this->insertErrorNotification($message, $error);
            return false;
        }

        // Write success to database
        $this->insertSuccessNotification($message);
        return true;
    }
}