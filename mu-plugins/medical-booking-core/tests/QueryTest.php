<?php

add_action('init', function () {
    add_action('wpcf7_before_send_mail', function ($contact_form) {
        // 1. LẤY ID FORM
        $form_id = $contact_form->id();

        // 2. LẤY DỮ LIỆU NGƯỜI DÙNG GỬI (your-name, your-email, ...)
        $submission = WPCF7_Submission::get_instance();
        if ($submission) {
            $posted_data = $submission->get_posted_data();
        } else {
            $posted_data = [];
        }

        // 3. GHI LOG (xem trong wp-content/debug.log)
        error_log("=== CF7 SUBMIT - Form ID: $form_id ===");
        error_log(print_r($posted_data, true));
    });
});
