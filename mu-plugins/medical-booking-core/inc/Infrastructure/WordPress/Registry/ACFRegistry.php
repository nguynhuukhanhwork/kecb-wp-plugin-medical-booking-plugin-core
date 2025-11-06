<?php

namespace MedicalBooking\Infrastructure\WordPress\Registry;

final class ACFRegistry extends RegistryBase {

    private static ?self $instance  = null;
    protected function getConfigPath(): string
    {
        return MB_INFRASTRUCTURE_PATH . 'WordPress/acf';
    }
    protected static function defineCacheKey(): string
    {
        return 'acf_register';
    }

    /**
     * Private constructor for Singleton
     */
    private function __construct() {
        parent::__construct();

        // Auto-register on init
        add_action('acf/init', [$this, 'register']);
    }

    /**
     * Get singleton instance
     */
    public static function getInstance(): self {
        if (self::$instance === null) {

            self::$instance = new self();
        }
        return self::$instance;
    }


    public function register(): bool {
        $configs = $this->getConfigs();

        if (empty($configs)) {
            kecb_error_log("[ACFRegistry] Empty configs");
            return false;
        }

        foreach ($configs as $config) {
            kecb_register_acf_field($config);
        }

        return true;
    }
}