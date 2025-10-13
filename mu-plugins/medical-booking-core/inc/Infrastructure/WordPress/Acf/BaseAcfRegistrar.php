<?php

namespace MedicalBooking\Infrastructure\Wordpress\Acf;

use function MedicalBooking\Helpers\kecb_check_file;
use function MedicalBooking\Helpers\kecb_register_acf_field_json;
use function MedicalBooking\Helpers\kecb_register_taxonomy_json;
use function MedicalBooking\Helpers\kecb_write_error_log;

abstract class BaseAcfRegistrar
{
    protected string $configPath;
    public function __construct(string $file_path) {
        $this->configPath = $file_path;
        add_action('acf/init', [$this, 'register']);
    }

    private function checkConfig(): bool
    {
        return kecb_check_file($this->configPath);
    }

    private static function checkPlugin(): bool
    {
        // Function register
        $function_register_acf = 'acf_add_local_field_group';

        return function_exists('acf_add_local_field_group');
    }

    public function register(): void
    {
        $config_file = $this->configPath;

        // Check file config
        if (!$this->checkConfig($config_file)) {
            return;
        }

        // Check function of Advanced Custom Fields
        if ($this->checkPlugin() === false) {
            return;
        }

        kecb_register_acf_field_json($config_file);
    }

}