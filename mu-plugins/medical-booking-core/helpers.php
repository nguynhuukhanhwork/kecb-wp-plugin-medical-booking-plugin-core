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
function kecb_register_post_type_json(string $config_file_path): bool
{
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
    if (!isset($config['post_type']) || !isset($config['args'])) {
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
function kecb_register_acf_field_json(string $config_file_path): void
{
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
 * @param string $file_path file JSON Config
 * @param string $prefix is prefix of Taxonomy
 * @param bool $insert_default_term flag for insert data
 * @return bool
 */
function kecb_register_taxonomy_json(string $file_path, string $prefix = '', bool $insert_default_term = false): bool
{

    // Check file Path
    if (!file_exists($file_path)) {
        kecb_write_error_log("File $file_path does not exist");
        return false;

    }

    // Check is JSON file
    $config = kecb_read_json($file_path);

    if (empty($config)) {
        kecb_write_error_log("File $file_path is empty");
        return false;
    }

    $taxonomy = $prefix . $config['taxonomy'];
    $types = $config['type'];
    $args = $config['args'];

    // Check Empty
    if (empty($taxonomy) || empty($args) || empty($types)) {
        kecb_write_error_log("Taxonomy Config is Empty (taxonomy, args or types) - File: $file_path");
        return false;
    }

    if (!taxonomy_exists($taxonomy)) {
        register_taxonomy($taxonomy, $types, $args);
    }

    $term = $config['terms'];

    // Không Insert Term
    if (!$insert_default_term) {
        return true;
    }

    // get Terms config
    $term_defaults = $config['terms'] ?? [];

    // Check Config Terms is empty
    if (empty($term)) {
        return true;
    }

    // Insert Terms
    foreach ($term_defaults as $term) {
        $name = is_array($term) ? $term['name'] : $term;
        $slug = is_array($term) && isset($term['slug']) ? $term['slug'] : sanitize_title($name);

        if (!term_exists($slug, $taxonomy)) {
            wp_insert_term($name, $taxonomy, ['slug' => $slug]);
        }
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