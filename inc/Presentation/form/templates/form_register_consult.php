<?php

use MedicalBooking\Infrastructure\DB\Taxonomy;
use MedicalBooking\Presentation\form\config\FormConfig;

$doctor_special = Taxonomy::getInstance();
$specials       = $doctor_special->getSpecificTerms();

// Get and Display Title and Description
if (isset($attrs['title']))  {
    echo '<h3>' . esc_html($attrs['title']) . '</h3>';
}

if (isset($attrs['description']))  {
    echo '<p>' . esc_html($attrs['description']) . '</p>';
}

// Config label
if (isset($attrs['label']))  {
    if ($attrs['label'] !== true)  {
        echo '<style> #mb_appointment_form label {display: none;} </style>';
    }
}

// Form
?>
<form id="mb_appointment_form" name="mb_appointment_form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
    <input type="hidden" name="action" value="<?php echo esc_attr(FormConfig::CONSULT_ACTION); ?>">
    <?php wp_nonce_field(FormConfig::CONSULT_ACTION, FormConfig::CONSULT_NONCE); ?>

    <fieldset>
        <label for="mb_appointment_form_name">Họ và tên</label>
        <input name="mb_appointment_form_name" id="mb_appointment_form_name">

        <label for="mb_appointment_form_email">Email:</label>
        <input name="mb_appointment_form_email" id="mb_appointment_form_email">

        <label for="mb_appointment_form_phone_number">Số điện thoại</label>
        <input name="mb_appointment_form_phone_number" id="mb_appointment_form_phone_number">

        <label for="mb_appoitment_form_birday">Năm sinh</label>
        <input type="date" name="mb_appointment_form_birth_year" id="mb_appoitment_form_birday">
    </fieldset>

    <fieldset>
        <label for="mb_appointment_form_date_appointment">Ngày khám</label>
        <input type="date" name="mb_appointment_form_date_appointment" id="mb_appointment_form_date_appointment">

        <label for="mb_appointment_form_time_appointment">Buổi khám</label>
        <select name="mb_appointment_form_time_appointment" id="mb_appointment_form_time_appointment">
            <option value=""></option>
            <option value="Buổi sáng">Buổi sáng (Trước 11h)</option>
            <option value="Buổi trưa">Buổi chiều (Trước 4h)</option>
        </select>

        <label for="mb_appointment_form_special">Chuyên khoa</label>
        <select name="mb_appointment_form_special" id="mb_appointment_form_special">
            <option value=""></option>
            <?php foreach ($specials as $special) : ?>
                <option value="<?php echo esc_attr($special); ?>">
                    <?php echo esc_html($special); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </fieldset>

    <fieldset>
        <label for="mb_appointment_form_note">Ghi chú</label>
        <textarea name="mb_appointment_form_note" id="mb_appointment_form_note" rows="10"></textarea>
    </fieldset>

    <fieldset>
        <button type="submit">Submit</button>
    </fieldset>
</form>

