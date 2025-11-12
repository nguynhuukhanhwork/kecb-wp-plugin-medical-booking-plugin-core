<?php

namespace TravelBooking\Infrastructure\Logger;

final class Logger
{
    private static string $log_dir_name = 'travel_booking';
    private static string $log_file_name = 'travel_log';
    private function __construct() {}
    private function __clone() {}
    public function __wakeup() {
        throw new \Exception("Cannot unserialize a singleton.");
    }

    /**
     * Write Log (Error, Activate Log)
     * @param string $message
     * @param string $log_type
     * @return bool
     */
    public static function log(string $message, string $log_type = 'error'): bool
    {
        $timestamp = date('Y-m-d H:i:s');
        $content = sprintf(
            "[%s] [Travel Booking] [%s] %s \n",
            $timestamp,
            $log_type,
            $message
        );

        // Config file name
        $log_file = WP_CONTENT_DIR . '/' . self::$log_dir_name . '/' . self::$log_file_name . '.log';

        // Check Permission
        $log_dir = dirname($log_file);
        if (!file_exists($log_dir)) {
            mkdir($log_dir, 0755, true);
        }

        return file_put_contents($log_file, $content, FILE_APPEND | LOCK_EX) !== false;
    }
}