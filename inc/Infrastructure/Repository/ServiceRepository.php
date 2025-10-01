<?php

namespace MedicalBooking\Infrastructure\Repository;

use function MedicalBooking\Helpers\kecb_register_acf_field_json;
use function MedicalBooking\Helpers\kecb_register_post_type_json;

class ServiceRepository
{
    /** @var string|null File config json path of post type 'service'  */
    protected ?string $cptConfigFilePath = null;
    /** @var string|null File config json path of fields for post type 'service' */
    protected ?string $acfConfigFilePath;
    public function __construct()
    {
        $this->cptConfigFilePath = MB_INFRASTRUCTURE_PATH . 'Config/cpt-json/service-cpt.json';
        $this->acfConfigFilePath = MB_INFRASTRUCTURE_PATH . 'Config/acf-json/service-fields.json';
        add_action('init', [$this, 'registerCpt']);
        add_action('init', [$this, 'registerAcfFields']);
    }

    /**
     * Register Post Type 'service' with JSON config
     *
     * @return void
     */
    public function registerCpt(): void
    {
       kecb_register_post_type_json($this->cptConfigFilePath);
    }

    /**
     * Register ACF Fields  for post type 'service'
     * @return void
     */
    public function registerAcfFields(): void
    {
      kecb_register_acf_field_json($this->acfConfigFilePath);
    }
}