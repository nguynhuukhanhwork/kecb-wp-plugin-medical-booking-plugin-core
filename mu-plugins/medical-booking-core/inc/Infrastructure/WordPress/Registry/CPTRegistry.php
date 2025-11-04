<?php
namespace MedicalBooking\Infrastructure\WordPress\Registry;

final class CPTRegistry extends RegistryBase {

    private static ?self $instance  = null;
    protected function getConfigPath(): string
    {
        return MB_INFRASTRUCTURE_PATH . 'WordPress/Config/cpt';
    }
    protected static function defineCacheKey(): string
    {
        return 'cpt_register';
    }

    protected static array $registered = [
        'tour' => 'tour',
        'booking' => 'booking',
    ];

    /**
     * Private constructor for Singleton
     */
    private function __construct() {
        parent::__construct();

        // Auto-register on init
        add_action('init', [$this, 'register']);
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

     /**
     * Register all CPTs (from cache or JSON)
     */
    public function register(): bool {
        $configs = $this->getConfigs();

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

    /**
     * Safe get post type name
     * @param string $post_type
     * @return string|false
     */
    public static function getPostTypes(string $post_type): string|false {
        $post_types = self::$registered;
        if (!array_key_exists($post_type, $post_types)) {
            return false;
        }
        return $post_types[$post_type];
    }
}
