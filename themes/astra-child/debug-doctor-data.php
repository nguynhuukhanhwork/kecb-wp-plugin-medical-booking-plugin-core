<?php
/**
 * Debug helper để kiểm tra dữ liệu ACF của bác sĩ
 * Chỉ sử dụng để debug, xóa file này sau khi hoàn thành
 */

// Chỉ chạy khi đang debug
if (!isset($_GET['debug_doctor']) || !current_user_can('manage_options')) {
    return;
}

$post_id = intval($_GET['post_id'] ?? 0);
if (!$post_id) {
    echo '<p>Vui lòng thêm ?debug_doctor=1&post_id=ID vào URL</p>';
    return;
}

echo '<h2>Debug Doctor Data for Post ID: ' . $post_id . '</h2>';

// Kiểm tra post type
$post = get_post($post_id);
if (!$post) {
    echo '<p>Post không tồn tại</p>';
    return;
}

echo '<h3>Post Info:</h3>';
echo '<pre>' . print_r([
    'ID' => $post->ID,
    'post_type' => $post->post_type,
    'post_title' => $post->post_title,
    'post_status' => $post->post_status
], true) . '</pre>';

// Kiểm tra tất cả ACF fields
echo '<h3>ACF Fields:</h3>';
$acf_fields = get_fields($post_id);
echo '<pre>' . print_r($acf_fields, true) . '</pre>';

// Kiểm tra từng field riêng biệt
$fields_to_check = [
    'doctor_phone',
    'doctor_email', 
    'doctor_qualification',
    'doctor_years_of_experience',
    'doctor_current_position',
    'doctor_department',
    'doctor_schedule',
    'doctor_bio'
];

echo '<h3>Individual Field Values:</h3>';
foreach ($fields_to_check as $field) {
    $value = get_field($field, $post_id);
    echo '<strong>' . $field . ':</strong> ';
    echo '<pre>' . print_r([
        'value' => $value,
        'type' => gettype($value),
        'is_array' => is_array($value),
        'is_string' => is_string($value),
        'is_null' => is_null($value),
        'is_empty' => empty($value)
    ], true) . '</pre>';
}

// Test DoctorService
echo '<h3>DoctorService Test:</h3>';
try {
    $doctor_service = new \MedicalBooking\Application\Service\DoctorService();
    $doctor = $doctor_service->getDoctorById($post_id);
    
    if ($doctor) {
        echo '<p>✅ DoctorService thành công</p>';
        echo '<pre>' . print_r([
            'ID' => $doctor->getId(),
            'Name' => $doctor->getName(),
            'Email' => $doctor->getEmail(),
            'Phone' => $doctor->getPhone(),
            'Schedule' => $doctor->getSchedule(),
            'Schedule Type' => gettype($doctor->getSchedule())
        ], true) . '</pre>';
    } else {
        echo '<p>❌ DoctorService trả về null</p>';
    }
} catch (Exception $e) {
    echo '<p>❌ DoctorService lỗi: ' . $e->getMessage() . '</p>';
    echo '<pre>' . $e->getTraceAsString() . '</pre>';
}

// Test DoctorPresenter
echo '<h3>DoctorPresenter Test:</h3>';
try {
    if (isset($doctor) && $doctor) {
        $formatted_data = \MedicalBooking\Presentation\DoctorPresenter::formatDoctorForDisplay($doctor);
        echo '<p>✅ DoctorPresenter thành công</p>';
        echo '<pre>' . print_r([
            'formatted_schedule' => $formatted_data['schedule'],
            'formatted_schedule_type' => gettype($formatted_data['schedule'])
        ], true) . '</pre>';
    }
} catch (Exception $e) {
    echo '<p>❌ DoctorPresenter lỗi: ' . $e->getMessage() . '</p>';
    echo '<pre>' . $e->getTraceAsString() . '</pre>';
}
?>
