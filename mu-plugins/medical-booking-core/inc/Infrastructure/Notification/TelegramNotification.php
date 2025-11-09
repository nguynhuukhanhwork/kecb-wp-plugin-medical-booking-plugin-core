<?php

namespace TravelBooking\Infrastructure\Notification;

use TravelBooking\Infrastructure\Notification\BaseNotification;

final class TelegramNotification extends BaseNotification
{
    public string $token_api = '';
    public string $chat_id = '';
    public string $url = '';
    private static ?self $instance = null;

    public static function getInstance(): self
    {
        return self::$instance ?? (self::$instance = new self());
    }
    public function __construct()
    {
        parent::__construct();
    }

    public function setup(): void
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
        // Set up
        $this->setup();

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