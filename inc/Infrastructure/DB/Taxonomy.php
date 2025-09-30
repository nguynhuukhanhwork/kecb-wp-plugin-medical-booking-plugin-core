<?php

namespace MedicalBooking\Infrastructure\DB;

use function MedicalBooking\Helpers\kecb_get_term_name;
use function MedicalBooking\Helpers\kecb_read_json;
use function MedicalBooking\Helpers\kecb_write_error_log;

class Taxonomy
{
    protected ?string $taxonomyConfigDir = null;
    protected static ?self $instance = null;

    public function __construct()
    {
        $this->taxonomyConfigDir = MB_INFRASTRUCTURE_PATH . 'Config/tax-json/';
        add_action('init', [$this, 'registerTaxonomies']);
    }

    public static function getInstance(): ?self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function registerTaxonomies(): void
    {
        $files = glob($this->taxonomyConfigDir . '*.json');

        if (!$files) {
            kecb_write_error_log("No taxonomy JSON files found in $this->taxonomyConfigDir");
            return;
        }

        foreach ($files as $file) {
            $config = kecb_read_json($file);

            if (empty($config) || !isset($config['taxonomy'], $config['type'], $config['args'])) {
                kecb_write_error_log("Invalid or empty taxonomy config in $file");
                continue;
            }

            // register taxonomy nếu chưa có
            if (!taxonomy_exists($config['taxonomy'])) {
                register_taxonomy($config['taxonomy'], $config['type'], $config['args']);
            }

            if (!empty($config['terms'])) {
                foreach ($config['terms'] as $term) {
                    $name = is_array($term) ? $term['name'] : $term;
                    $slug = is_array($term) && isset($term['slug']) ? $term['slug'] : sanitize_title($name);

                    if (!term_exists($slug, $config['taxonomy'])) {
                        wp_insert_term($name, $config['taxonomy'], ['slug' => $slug]);
                    }
                }
            }
        }
    }

    public function getSpecificTerms(): array
    {
        return kecb_get_term_name('speciality', false);
    }
}
