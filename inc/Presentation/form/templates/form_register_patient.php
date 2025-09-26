<?php

function mb_form_register_shortcode($atts, $content = null): string {
    $atts = shortcode_atts([
            'id' => 'medical_booking_form',
            'title' => '',
            'description' => '',
    ], $atts, 'medical_booking_form_register');

    ob_start();

    if (!empty($atts['title'])) {
        echo '<h2>' . esc_html($atts['title']) . '</h2>';
    }
    if (!empty($atts['description'])) {
        echo '<p>' . esc_html($atts['description']) . '</p>';
    }

    ?>
    <form id="<?php echo esc_attr($atts['id']); ?>" method="post">
        <?php wp_nonce_field('medical_booking_form_register_action', 'medical_booking_form_register_nonce'); ?>

        <label for="mb_name">Họ và tên</label>
        <input type="text" name="name" id="mb_name" required>

        <label for="mb_email">Email</label>
        <input type="email" name="email" id="mb_email" required>

        <label for="mb_phone">Số điện thoại</label>
        <input type="tel" name="phone" id="mb_phone">

        <label for="mb_note">Ghi chú</label>
        <textarea name="note" id="mb_note" rows="5"></textarea>

        <button type="submit" name="medical_booking_submit">Đăng ký tư vấn</button>
    </form>
    <?php

    return ob_get_clean();
}

// Đăng ký shortcode trong hook init
add_action('init', function() {
    add_shortcode('medical_booking_form_register', __NAMESPACE__ . '\\mb_form_register_shortcode');
});
