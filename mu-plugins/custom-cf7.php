<?php

add_action('wpcf7_before_send_mail', function($contact_form) {
    if ($contact_form->id() == 551) { // chỉ xử lý cho form ID 551

        $submission = WPCF7_Submission::get_instance();
        if ($submission) {
            $data = $submission->get_posted_data();

            // Lấy dữ liệu từ các field
            $name  = sanitize_text_field($data['patient-name']);
            $email = sanitize_email($data['patient-email']);
            $tel   = sanitize_text_field($data['patient-tel']);
            $date  = sanitize_text_field($data['patient-date']);
            $note  = sanitize_textarea_field($data['patient-note']);

            // Ghép nội dung log
            $log  = "===== Đăng ký tư vấn =====\n";
            $log .= "Họ và tên: $name\n";
            $log .= "Email: $email\n";
            $log .= "SĐT: $tel\n";
            $log .= "Thời gian tư vấn: $date\n";
            $log .= "Ghi chú: $note\n";
            $log .= "Thời gian gửi: " . current_time('mysql') . "\n\n";

            // Đường dẫn file trong uploads
            $upload_dir = wp_upload_dir();
            $file_path  = $upload_dir['basedir'] . '/cf7-consulting.txt';

            // Ghi dữ liệu (append vào cuối file)
            file_put_contents($file_path, $log, FILE_APPEND | LOCK_EX);
        }
    }
});


/**
 * Add data -> Select form
 */
/*add_filter('wpcf7_form_elements', function($content) {
    if (strpos($content, 'name="doctor-select"') !== false) {
        $doctors = get_posts([
            'post_type'      => 'doctor',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
        ]);

        $options = '<option value="">-- Chọn bác sĩ --</option>';
        foreach ($doctors as $doctor) {
            $options .= '<option value="' . esc_attr($doctor->post_title) . '">' . esc_html($doctor->post_title) . '</option>';
        }

        // Thêm option vào select
        $content = preg_replace(
            '/(<select[^>]*name="doctor-select"[^>]*>)/',
            '$1' . $options,
            $content
        );
    }

    return $content;
});*/
/*add_filter('wpcf7_form_tag_data_option', 'dynamic_doctor_list', 10, 3);
function dynamic_doctor_list($n, $options, $args)
{
    // Kiểm tra nếu select field có class 'doctor-list'
    if (in_array('doctor-list', $options)) {
        $doctors = array();

        // Query để lấy danh sách bác sĩ từ CPT
        $doctor_args = array(
            'post_type' => 'doctor',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
            'post_status' => 'publish'
        );

        $doctor_query = new WP_Query($doctor_args);

        if ($doctor_query->have_posts()) {
            while ($doctor_query->have_posts()) {
                $doctor_query->the_post();
                $doctors[] = get_the_title();
            }
            wp_reset_postdata();
        }

        return $doctors;
    }
}*/


