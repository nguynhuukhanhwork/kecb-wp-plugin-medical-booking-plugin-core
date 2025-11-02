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
        CPTRegistry::get_instance()->register();
        ACFRegistry::get_instance()->register();
        TaxonomyRegistry::get_instance()->register();
    }
}
