<?php

namespace MedicalBooking\Presentation\form\config;

final class FormConfig
{
    // Config cho form Consult
    public const CONSULT_ACTION = 'mb_form_register_consult_submit';
    public const CONSULT_NONCE  = 'mb_form_register_consult_submit_nonce';

    // Config cho form Patient
    public const PATIENT_ACTION = 'mb_form_register_patient_submit';
    public const PATIENT_NONCE  = 'mb_form_register_patient_submit_nonce';

    private function __construct() {} // Không cho khởi tạo class
}
