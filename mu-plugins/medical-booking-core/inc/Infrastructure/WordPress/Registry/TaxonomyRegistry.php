<?php
namespace MedicalBooking\Infrastructure\WordPress\Registry;

final class TaxonomyRegistry extends RegistryBase
{

    private static ?self $instance  = null;
    protected function getConfigPath(): string
    {
        return MB_INFRASTRUCTURE_PATH . 'WordPress/Config/tax';
    }
    protected static function defineCacheKey(): string
    {
        return 'tax_register';
    }

    /**
     * Private constructor for Singleton
     */
    private function __construct() {
        parent::__construct();

        // Auto-register on init
        add_action('init', [$this, 'register'], 0);

    }

    /**
     * Get singleton instance
     */
    public static function get_instance(): self {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function register(): bool {
      $configs = $this->getConfigs();

      if (empty($configs)) {
        kecb_error_log("[TaxonomyRegistry] Empty configs");
        return false;
      }

      foreach ($configs as $config) {
          kecb_register_taxonomy($config);
      }

      return true;

    }
}