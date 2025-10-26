<?php
/**
 * Plugin Name: KhanhECB Helper Function WP
 * Description: All Helper Function
 * Features: Write Log, Read Json, Register ACF with JSON, Register CPT with JSON
 * Version: 1.0.0
 */

/**
 * Get Log folder path of Medical Booking System Log
 * @return string
 */
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

function kecb_error_log(string $error): bool
{
    $prefix_log = '[Medical Booking System]';
    return error_log($prefix_log . $error);
}

/**
 * Ghi log lỗi
 * @param $message string is content of error log
 */
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
    // Check file is exists
    if (!file_exists($path)) {
        kecb_write_error_log("JSON file not found: $path");
        return [];
    }

    // Check file readable
    if (!is_readable($path)) {
        kecb_write_error_log("JSON file is not readable: $path");
        return [];
    }

    // Check file empty
    if (filesize($path) === 0 ) {
        kecb_write_error_log("JSON file is empty: $path");
        return [];
    }

    // Check file large
    if (filesize($path) > 1024 * 10 ) {
        kecb_write_error_log("JSON file is large: $path");
        return [];
    }

    // Get data and decode json
    $json = file_get_contents($path);
    $data = json_decode($json, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        kecb_write_error_log("JSON decode error ($path): " . json_last_error_msg());
        return [];
    }

    return $data;
}

/**
 * Lấy tất cả config files theo extension
 * @param string $extension Là đuôi file
 * @param string $dir_path  Đường dẫn thư mục
 * @return array Mảng đường dẫn files hợp lệ
 */
function kecb_get_all_files_dir(string $dir_path, string $extension = 'json'): array {

    // Normalize path với trailing slash
    $dir_path = trailingslashit($dir_path);

    // Check directory exists
    if (!is_dir($dir_path)) {
        kecb_error_log("Directory not found: $dir_path");
        return [];
    }

    // Check readable
    if (!is_readable($dir_path)) {
        kecb_error_log("Directory not readable: $dir_path");
        return [];
    }

    // Get all files with extension
    $all_files = glob($dir_path . '*.' . $extension );

    // Check if no files found
    if (empty($all_files)) {
        kecb_error_log("No JSON files found in: $dir_path");
        return [];
    }

    // Filter valid files
    $result = array_filter($all_files, function($file) {
        // Chỉ lấy files (không phải directory)
        if (!is_file($file)) {
            kecb_error_log("Not a file: $file");
            return false;
        }

        // Check readable
        if (!is_readable($file)) {
            kecb_error_log("File not readable: $file");
            return false;
        }

        // Check file size (0 bytes = invalid)
        if (filesize($file) === 0) {
            kecb_error_log("File is empty: $file");
            return false;
        }

        // Check file size max (10KB như helper của bạn)
        if (filesize($file) > 1024 * 10) {
            kecb_error_log("File too large (>10KB): $file");
            return false;
        }

        return true;
    });

    // Re-index array (array_filter giữ nguyên keys)
    return array_values($result);
}

/**
 * Đăng ký Post Type dựa trên cấu hình JSON.
 *
 * @param array $config là dữ liệu cấu hình của CPT
 * @return bool
 */
function kecb_register_post_type(array $config): bool {

    // Check empty config
    if (empty($config)) {
        kecb_error_log("Config is empty");
        return false;
    }

    // Check data validate
    if (!isset($config['post_type']) || !isset($config['args'])) {
        kecb_error_log("CPT config error: Invalid post type or args");
        return false;
    }

    $post_type = $config['post_type'];
    $args = $config['args'];

    if (post_type_exists($config['post_type'])) {
        kecb_error_log("CPT config error: Post Type $post_type is exists");
        return false;
    }

    $result = register_post_type($post_type, $args);

    if (is_wp_error($result)) {
        kecb_error_log($result->get_error_message());
        return false;
    }

    return true;
}

/**
 * Đăng ký ACF Field dựa trên cấu hình JSON.
 *
 * @param string $config is array config ACF
 * @return bool
 */
function kecb_register_acf_field(array $config): bool
{
    // Check empty
    if (empty($config)) {
        kecb_error_log("ACF Config file is empty");
        return false;
    }

    // Check structure theo chuẩn ACF JSON
    if (!isset($config['key']) || !isset($config['title']) || !isset($config['fields']) || !isset($config['location'])) {
        kecb_error_log("ACF file config is invalid");
        return false;
    }

    // Register ACF Field
    $result = acf_add_local_field_group($config);

    if(is_wp_error($result))
    {
        kecb_error_log("[ACF config error]");
        return false;
    }

    return true;
}

/**
 *
 * @param $taxonomy string tên Taxonomy
 * @param $hide_empty bool lấy các Taxonomy không có gắn với post nào
 * @return array
 */
function kecb_get_term_name(string $taxonomy, bool $hide_empty = false): array
{

    // Config
    $terms = get_terms([
        'taxonomy' => $taxonomy,
        'hide_empty' => $hide_empty,
    ]);

    $terms_name = [];

    if (!is_wp_error($terms)) {
        foreach ($terms as $term) {
            $terms_name[] = $term->name;
        }
    }

    return $terms_name;
}

/**
 * Register Taxonomy WP and Insert date to database
 * @param array $config is JSON file config
 * @param string $prefix is prefix of Taxonomy
 * @return bool
 */
function kecb_register_taxonomy(array $config, string $prefix = ''): bool
{
    if (empty($config)) {
        kecb_error_log("Config is empty");
        return false;
    }

    $taxonomy = $prefix . $config['taxonomy'];
    $types = $config['post_types'] ?? $config['type'];
    $args = $config['args'];

    // Check Empty
    if (empty($taxonomy) || empty($args) || empty($types)) {
        kecb_error_log("Taxonomy Config is Empty (taxonomy, args or types)");
        return false;
    }

    if (taxonomy_exists($taxonomy)) {
        kecb_error_log("Taxonomy is exists");
        return false;
    }

    $result = register_taxonomy($taxonomy, $types, $args);

    if (is_wp_error($result)) {
        kecb_write_error_log($result->get_error_message());
        return false;
    }

    return true;
}

/**
 * Hiển thị bảng dữ liệu trong trang Admin WordPress (header là mảng thường).
 *
 * @param array $header Danh sách tiêu đề cột, ví dụ: ['Tên', 'Email', 'Điện thoại']
 * @param array $data   Mảng dữ liệu, mỗi phần tử là 1 hàng dạng array tuần tự.
 */
function kecb_admin_show_data(array $header, array $data): void
{
    if (empty($header)) {
        echo '<p><strong>Lỗi:</strong> Không có tiêu đề cột để hiển thị.</p>';
        return;
    }
    ?>
    <div class="wrap">
        <table class="wp-list-table widefat fixed striped">
            <thead>
            <tr>
                <?php foreach ($header as $label): ?>
                    <th scope="col"><?php echo esc_html($label); ?></th>
                <?php endforeach; ?>
            </tr>
            </thead>
            <tbody>
            <?php if (!empty($data)): ?>
                <?php foreach ($data as $row): ?>
                    <tr>
                        <?php foreach ($row as $value): ?>
                            <td>
                                <?php
                                if (is_array($value) || is_object($value)) {
                                    echo '<pre>' . esc_html(json_encode($value, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)) . '</pre>';
                                } else {
                                    echo esc_html($value);
                                }
                                ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="<?php echo count($header); ?>">
                        <?php esc_html_e('Không có dữ liệu để hiển thị.', 'medical-booking'); ?>
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php
}

/**
 * Get data from database of Contact Form 7
 * @param int $form_id post type id of form
 * @param int $limit row of data
 * @return array
 */
function kecb_get_form_submission_cf7(int $form_id, int $limit = 30): array
{
    global $wpdb;

    $table = $wpdb->prefix . 'db7_forms';

    // Thực thi truy vấn với prepare để tránh SQL injection
    $rows = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT form_value, form_date 
             FROM {$table} 
             WHERE form_post_id = %d
             ORDER BY form_date DESC 
             LIMIT %d",
            $form_id,
            $limit
        ),
        ARRAY_A
    );

    if ($wpdb->last_error) {
        error_log("DB7 query error: {$wpdb->last_error}");
        return [];
    }

    // Dùng array_map thay vì foreach để gọn và tránh lặp
    return array_values(array_filter(array_map(function ($row) {
        $data = @unserialize($row['form_value']);
        if (!is_array($data)) {
            return null;
        }
        $data['form_date'] = $row['form_date'];
        return $data;
    }, $rows)));
}

/**
 * Check file basic: file is exist, file can read, file not empty
 * @param string $file_path is dir file path
 * @param int $limit_size is max size file (KB)
 * @return bool
 */
function kecb_check_file(string $file_path, int $limit_size): bool {
    $path = realpath($file_path);
    if ($path === false || !file_exists($path)) {
        error_log("[KECB_CHECK_FILE] File not found: $file_path");
        return false;
    }

    if (!is_readable($path)) {
        error_log("[KECB_CHECK_FILE] File not readable: $file_path");
        return false;
    }

    $file_size = filesize($path);

    if ($file_size === 0) {
        error_log("[KECB_CHECK_FILE] File is empty: $file_path");
        return false;
    }

    if ($file_size > $limit_size) {
        error_log("[KECB_CHECK_FILE] File large: $file_path");
    }

    return true;
}