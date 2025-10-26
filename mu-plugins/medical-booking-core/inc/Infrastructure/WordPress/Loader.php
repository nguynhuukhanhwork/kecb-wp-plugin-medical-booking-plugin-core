<?php

/**
 * Class Load Register Post Type, ACF, Taxonomy
 * @since 1.0.0
 */

namespace MedicalBooking\Infrastructure\WordPress;

use MedicalBooking\Infrastructure\WordPress\Registry\ACFRegistry;
use MedicalBooking\Infrastructure\WordPress\Registry\CPTRegistry;
use MedicalBooking\Infrastructure\WordPress\Registry\TaxonomyRegistry;

final class Loader
{
    private static ?self $instance = null;
    private array $config = [];
    private function __construct()
    {
        $this->config = [
            'cpt' => MB_INFRASTRUCTURE_PATH . 'WordPress/Config/cpt',
            'acf' => MB_INFRASTRUCTURE_PATH . 'WordPress/Config/acf',
            'tax' => MB_INFRASTRUCTURE_PATH . 'WordPress/Config/tax'
        ];
    }
    private function __clone() {}
    public function __wakeup() {}

    public static function get_instance(): self
    {
        return self::$instance ??= new self();
    }

    public function boot(): void
    {
        add_action('init', [$this, 'register_all'], 5);
    }

    public function register_all(): void
    {
        CPTRegistry::get_instance($this->config['cpt'])->register();
        ACFRegistry::get_instance($this->config['acf'])->register();
        TaxonomyRegistry::get_instance($this->config['tax'])->register();
    }
}
