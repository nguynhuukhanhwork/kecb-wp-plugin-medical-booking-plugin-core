<?php

namespace MedicalBooking\Infrastructure\WordPress\Cpt;

use MedicalBooking\Infrastructure\Config\ConfigReader;
use function MedicalBooking\Helpers\kecb_register_post_type_json;
use function MedicalBooking\Helpers\kecb_write_error_log;

abstract class BaseCptRegistrar
{
    protected string $cptConfigPath;

    public function __construct(string $cptConfigPath){
        $this->cptConfigPath = $cptConfigPath;
        add_action('init', [$this, 'register']);
    }

    public function register(): void{
        $config = ConfigReader::getConfigFromJson($this->cptConfigPath);

        if (empty($config)) {
            kecb_write_error_log(static::class . "Config empty: {$this->cptConfigPath}");
            return;
        }

        kecb_register_post_type_json($this->cptConfigPath);
    }
}