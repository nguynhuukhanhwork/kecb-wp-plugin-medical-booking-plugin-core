<?php

namespace MedicalBooking\Infrastructure\Repository;

use function MedicalBooking\Helpers\kecb_register_acf_field_json;
use function MedicalBooking\Helpers\kecb_register_post_type_json;

class PatientRepository
{
    private string $cptJsonFilePath;
    private string $acfJsonFilePath;

    public function __construct()
    {
        // Register CPT and Acf Field for Patient
        $this->cptJsonFilePath = MB_INFRASTRUCTURE_PATH . 'Config/cpt-json/patient-cpt.json';
        $this->acfJsonFilePath = MB_INFRASTRUCTURE_PATH . 'Config/acf-json/patient-fields.json';
        add_action('init', [$this, 'registerPostType']);
        add_action('acf/init', [$this, 'registerAcfFields']);
    }

    public function registerPostType(): void
    {
        kecb_register_post_type_json($this->cptJsonFilePath);
    }

    public function registerAcfFields(): void
    {
        kecb_register_acf_field_json($this->acfJsonFilePath);
    }
}
