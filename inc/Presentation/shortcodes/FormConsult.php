<?php
namespace MedicalBooking\Presentation\shortcodes;

final class FormConsult
{
    public function __construct() {
        add_shortcode('user_appointment_form', [$this, 'render']);
    }

    public function render($attrs): string {
        $attrs = shortcode_atts([
            'title' =>   '',
            'description' => '',
            'label' => true
        ], $attrs, 'mb_user_appointment_form');

        ob_start();
        require_once MBS_CORE_PATH . 'medical-booking-core/inc/Presentation/form/templates/form_register_consult.php';
        return ob_get_clean();
    }
}
