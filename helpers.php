<?php
/**
 * Plugin Name: KhanhECB Helper Function WP
 * Description: All Helper Function
 * Features: Write Log, Read Json, Register ACF with JSON, Register CPT with JSON
 * Version: 1.0.0
 */
namespace MedicalBooking\Helpers;

/**
 * Get Log folder path of Medical Booking System Log
 * @return string
 */
/** @noinspection PhpUnused */
function kecb_get_log_dir(): string
{
    $upload_dir = wp_upload_dir();
    $base_dir = $upload_dir['basedir'];
    return $base_dir . '/medical-booking-system-log';
}

/**
 * Hàm base để ghi log vào file
 */
function kecb_write_log(string $filename, string $prefix, string $message): bool
{
    $folder_path = kecb_get_log_dir();
    if (!file_exists($folder_path)) {
        wp_mkdir_p($folder_path);
    }

    $file_path = rtrim($folder_path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $filename;
    $logline = sprintf("[%s] %s: %s%s", date('Y-m-d H:i:s'), $prefix, $message, PHP_EOL);

    return file_put_contents($file_path, $logline, FILE_APPEND | LOCK_EX) !== false;
}
/**
 * Ghi log lỗi
 * @param $message string is content of error log
 */
/** @noinspection PhpUnused */
function kecb_write_error_log(string $message): bool
{
    $date = date('Y-m-d H:i:s');
    $prefix = 'MedicalBooking System Error ' . $date;
    return kecb_write_log('errors.log', $prefix, $message);
}

/**
 * Ghi log hoạt động
 * @param $message string is content of activate log
 */
/** @noinspection PhpUnused */
function kecb_write_activity_log(string $message): bool
{
    $date = date('Y-m-d H:i:s');
    $prefix = 'MedicalBooking System Activity ' . $date;
    return kecb_write_log('activity.log', $prefix, $message);
}

/**
 * Đọc JSON từ file, trả về array hoặc empty array nếu lỗi
 *
 * @param string $path Path tới file JSON
 * @return array
 */
function kecb_read_json(string $path): array
{
    if (!file_exists($path)) {
        kecb_write_error_log("JSON file not found: $path");
        return [];
    }

    $json = file_get_contents($path);
    $data = json_decode($json, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        kecb_write_error_log("JSON decode error ($path): " . json_last_error_msg());
        return [];
    }

    return $data;
}

/**
 * Đăng ký Post Type dựa trên cấu hình JSON.
 *
 * @param string $config_file_path
 * @return bool
 */
function kecb_register_post_type_json(string $config_file_path): bool {
    // Check file exists
    if (!file_exists($config_file_path)) {
        kecb_write_error_log("File $config_file_path does not exist");
        return false;
    }

    // If file exists -> get data
    $config = kecb_read_json($config_file_path);

    // Check Empty
    if (empty($config)) {
        kecb_write_error_log("CPT config error: File $config_file_path is empty");
    }

    // Check data
    if ( !isset($config['post_type']) || !isset($config['args'])) {
        kecb_write_error_log("CPT config error: Invalid post type or args");
    }

    register_post_type($config['post_type'], $config['args']);

    return true;
}

/**
 * Đăng ký ACF Field dựa trên cấu hình JSON.
 *
 * @param string $config_file_path
 * @return void
 */
function kecb_register_acf_field_json(string $config_file_path): void {
    // Check file exists
    if (!file_exists($config_file_path)) {
        kecb_write_error_log("File $config_file_path does not exist");
        return;
    }

    // Get Config
    $config = kecb_read_json($config_file_path);

    if (empty($config)) {
        kecb_write_error_log("ACF Config file is empty, file path $config_file_path");
        return;
    }

    // Check structure theo chuẩn ACF JSON
    if (!isset($config['key']) || !isset($config['title']) || !isset($config['fields']) || !isset($config['location'])) {
        kecb_write_error_log("ACF file config is invalid: file path $config_file_path");
        return;
    }

    // Register ACF Field
    acf_add_local_field_group($config);
}

function kecb_register_tax(): void {
    echo "Chưa viết gì";
}

/**
 *
 * @param $taxonomy string tên Taxonomy
 * @param $hide_empty bool lấy các Taxonomy không có gắn với post nào
 * @return array
 */
function kecb_get_term_name(string $taxonomy, bool $hide_empty = false): array {

    // Config
    $terms = get_terms([
        'taxonomy'   => $taxonomy,
        'hide_empty' => $hide_empty,
    ]);

    $terms_name = [];

    if (! is_wp_error($terms)) {
        foreach ($terms as $term) {
            $terms_name[] = $term->name;
        }
    }

    return $terms_name;
}