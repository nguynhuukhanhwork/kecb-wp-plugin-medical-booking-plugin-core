<?php

namespace MedicalBooking\Infrastructure\DB;

use RuntimeException;
use wpdb;
use function ElementorDeps\DI\string;
use function MedicalBooking\Helpers\kecb_read_json;
use function MedicalBooking\Helpers\kecb_write_error_log;

final class ConfigDb
{
    private static ?self $instance = null;
    private wpdb $wpdb;

    /**
     * Prefix mặc định cho custom table
     * (sẽ nối thêm với prefix WP)
     */
    private const CUSTOM_TABLE_PREFIX = 'mb_';

    /**
     * Đường dẫn thư mục config (được inject từ hằng global)
     */
    private string $configDir;

    /**
     * Tên file chứa trạng thái install flag
     */
    private const INSTALL_FLAG_FILE = 'constants.json';

    private function __construct()
    {
        global $wpdb;

        if (!($wpdb instanceof wpdb)) {
            throw new RuntimeException('Global $wpdb instance is not available.');
        }

        $this->wpdb = $wpdb;
        $this->configDir = rtrim(MB_INFRASTRUCTURE_PATH, '/') . '/Config/';
    }

    /**
     * Singleton pattern
     */
    public static function getInstance(): self
    {
        return self::$instance ??= new self();
    }

    /**
     * Đường dẫn file flag
     * @return string
     */
    private function getConstantFile(): string
    {
        return $this->configDir . self::INSTALL_FLAG_FILE;
    }

    /**
     * Lấy prefix đầy đủ cho custom table
     * @return string
     */
    public function getTablePrefix(): string
    {
        return $this->wpdb->prefix . self::CUSTOM_TABLE_PREFIX;
    }

    /**
     * Lấy tên table đầy đủ với prefix
     * @param string $tableName
     * @return string
     */
    public function getTableName(string $tableName): string
    {
        return $this->getTablePrefix() . $tableName;
    }

    /**
     * Lấy danh sách tên các table chính
     * @return array
     */
    public function getMainTableNames(): array
    {
        return [
            'bookings' => $this->getTableName('bookings'),
        ];
    }

    /**
     * Lấy toàn bộ trạng thái cài đặt (Install Flag)
     * @return array
     */
    public function getConstants(): array
    {
        $filePath = $this->getConstantFile();

        if (!file_exists($filePath)) {
            kecb_write_error_log('Install flag file not found: ' . $filePath);
            return [];
        }

        $data = kecb_read_json($filePath);

        if (empty($data)) {
            kecb_write_error_log('Install flag file is empty: ' . $filePath);
            return [];
        }

        return $data;
    }

    /**
     * Kiểm tra 1 flag cụ thể đã được bật chưa
     */
    public function isInstalled(string $flagKey): bool
    {
        $flags = $this->getConstants();
        return !empty($flags[$flagKey]) && $flags[$flagKey] === true;
    }

    public function setFlags(string $keyFlags, bool $valueFlags): bool
    {
        $filePath = $this->getConstantFile();
        $config = $this->getConstants();

        // Nếu file chưa tồn tại, tạo mảng trống
        if (!is_array($config)) {
            return false;
        }

        // Cập nhật giá trị
        $config[$keyFlags] = $valueFlags;

        // Ghi JSON đẹp, có format
        $json = json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        // Kiểm tra quyền ghi
        if (!is_writable(dirname($filePath))) {
            kecb_write_error_log("Config directory not writable: " . dirname($filePath));
            return false;
        }

        // Ghi dữ liệu vào file
        $result = file_put_contents($filePath, $json, LOCK_EX);

        if ($result === false) {
            kecb_write_error_log("Failed to write install flag file: $filePath");
            return false;
        }

        // Đảm bảo permission đúng (640)
        @chmod($filePath, 0640);

        return true;
    }

    /**
     * Get ID form Contact Form 7
     * @return int
     */
    public function getIdFormBooking(): int {
        $const =  $this->getConstants('form_booking_id_cf7');
        return (int) $const['form_booking_id_cf7'];
    }

}