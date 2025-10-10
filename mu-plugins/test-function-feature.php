<?php

use MedicalBooking\Infrastructure\Repository\FormSubmissionRepository;
use MedicalBooking\Helpers;

/*if (!defined('ABSPATH')) {
    exit;
}*/

//$columns = ['Demo', 'Test', 'Id', 'Name'];
//$form_submit = new FormSubmissionRepository(551);
//$data = $form_submit->getDataFromSubmission();
//
//foreach ($data as $column) {
//    $form_value = $column['form_value'];
//    $form_data = $column['form_date'];
//    error_log(print_r($form_value, true));
//}

$data = Helpers\kecb_get_form_submission_cf7(551);


