<?php
/**
 * Plugin Name: KhanhECB Setup SMTP
 * Description: Thiết lập cấu hình gửi mail SMTP qua Gmail.
 * Author: KhanhECB
 */

add_action('phpmailer_init', function($phpmailer) {
    $phpmailer->isSMTP();
    $phpmailer->Host       = 'smtp.gmail.com';
    $phpmailer->Port       = 587;
    $phpmailer->SMTPAuth   = true;
    $phpmailer->Username   = 'khanhecb@gmail.com';
    $phpmailer->Password   = 'dmzimjarmfzwxduy'; // App password Gmail
    $phpmailer->SMTPSecure = 'tls';

    $phpmailer->From       = 'khanhecb@gmail.com';
    $phpmailer->FromName   = 'KhanhECB Medical Booking System';
});

// Test gửi mail (chạy 1 lần, rồi comment lại)
add_action('init', function() {
    if (isset($_GET['test_smtp'])) {
        $to = 'khanhecb@gmail.com';
        $subject = 'Test SMTP from KhanhECB MU Plugin';
        $message = '✅ Nếu bạn nhận được email này, SMTP đã hoạt động thành công.';
        $headers = ['Content-Type: text/html; charset=UTF-8'];

        if (wp_mail($to, $subject, $message, $headers)) {
            wp_die('✅ Email đã được gửi thành công.');
        } else {
            wp_die('❌ Gửi thất bại. Kiểm tra log hoặc config SMTP.');
        }
    }
});
