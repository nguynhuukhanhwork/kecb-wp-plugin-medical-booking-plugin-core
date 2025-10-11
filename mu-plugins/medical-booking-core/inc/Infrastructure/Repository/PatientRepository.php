<?php

namespace MedicalBooking\Infrastructure\Repository;

use MedicalBooking\Infrastructure\Config\ConfigReader;
use function MedicalBooking\Helpers\kecb_register_acf_field_json;
use function MedicalBooking\Helpers\kecb_register_post_type_json;

final class PatientRepository
{
    /** @var string CPT config file name */
    public const CPT_CONFIG_FILE = 'patient-cpt.json';
    
    private string $cptJsonFilePath;
    private string $acfJsonFilePath;

    /**
     * Lấy post type name từ config (dynamic)
     * 
     * @return string
     */
    private static function getPostType(): string
    {
        return ConfigReader::getPostTypeFromJson(
            MB_INFRASTRUCTURE_PATH . 'Config/cpt-json/' . self::CPT_CONFIG_FILE
        );
    }

    public function __construct()
    {
        // Register CPT and Acf Field for Patient
        $this->cptJsonFilePath = MB_INFRASTRUCTURE_PATH . 'Config/cpt-json/' . self::CPT_CONFIG_FILE;
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
