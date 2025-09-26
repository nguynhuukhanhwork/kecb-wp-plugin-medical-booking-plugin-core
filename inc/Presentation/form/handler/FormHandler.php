<?php

namespace MedicalBooking\Presentation\Ajax;

use function MedicalBooking\Helpers\kecb_write_error_log;

if (!defined('ABSPATH')) {
    exit;
}

class FormHandler
{
    private string $form_register_consult_action = 'mb_form_consult_submit';
    private string $form_register_consult_nonce  = 'mb_form_consult_submit_nonce';

    public function __construct()
    {
        // Handler form Consult
        add_action("admin_post_{$this->form_register_consult_action}", [$this, 'handleFormRegisterConsult']);
        add_action("admin_post_nopriv_{$this->form_register_consult_action}", [$this, 'handleFormRegisterConsult']);

        // Handler form Patient (Register Appointment)
    }

    public function handleFormRegisterConsult(): void
    {

        // 1. Check nonce
        if (
            !isset($_POST[$this->form_register_consult_nonce]) ||
            !wp_verify_nonce($_POST[$this->form_register_consult_nonce], $this->form_register_consult_action)
        ) {
            wp_send_json_error(['message' => 'Xác thực không hợp lệ!']);
        }

        // 2. Lấy và validate dữ liệu
        $name    = sanitize_text_field($_POST['mb_appointment_form_name'] ?? '');
        $email   = sanitize_email($_POST['mb_appointment_form_email'] ?? '');
        $phone   = sanitize_text_field($_POST['mb_appointment_form_phone_number'] ?? '');
        $birth   = sanitize_text_field($_POST['mb_appointment_form_birth_year'] ?? '');
        $date    = sanitize_text_field($_POST['mb_appointment_form_date_appointment'] ?? '');
        $time    = sanitize_text_field($_POST['mb_appointment_form_time_appointment'] ?? '');
        $special = sanitize_text_field($_POST['mb_appointment_form_special'] ?? '');
        $note    = sanitize_textarea_field($_POST['mb_appointment_form_note'] ?? '');

        if (empty($name) || empty($email)) {
            wp_send_json_error(['message' => 'Vui lòng nhập đầy đủ họ tên và email!']);
        }

        // 3. Xử lý (ví dụ: lưu DB, gửi email...)
        // TODO: implement DB insert / gửi mail tại đây

        // 4. Response JSON (trả cả redirect URL nếu muốn chuyển hướng)
        wp_send_json_success([
            'message'  => "Cảm ơn $name, chúng tôi đã nhận thông tin đăng ký.",
            'redirect' => 'https://h-yourdesign.com'
        ]);
    }

    public function handlerFormPatient(): void {

    }
}
