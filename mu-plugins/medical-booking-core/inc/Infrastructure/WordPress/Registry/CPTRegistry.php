<?php
namespace MedicalBooking\Infrastructure\WordPress\Registry;

final class CPTRegistry extends RegistryBase {

    private static ?self $instance  = null;
    protected string $cache_key = 'medical_booking_cpt_register';

    /**
     * Private constructor for Singleton
     */
    private function __construct(string $config_dir_path) {
        parent::__construct($config_dir_path, $this->cache_key);

        // Auto-register on init
        add_action('init', [$this, 'register'], 0);
    }

    /**
     * Get singleton instance
     */
    public static function get_instance(string $config_dir_path = ''): self {
        if (self::$instance === null) {
            self::$instance = new self($config_dir_path);
        }
        return self::$instance;
    }

     /**
     * Register all CPTs (from cache or JSON)
     */
    public function register(): bool {
        $configs = $this->get_configs();

        // Check configs
        if (empty($configs)) {
            kecb_error_log('[CPTRegistry] No JSON files found.');
            return false;
        }

        // Register CPT
        foreach ($configs as $config) {
            kecb_register_post_type($config);
        }

        return true;
    }
}
