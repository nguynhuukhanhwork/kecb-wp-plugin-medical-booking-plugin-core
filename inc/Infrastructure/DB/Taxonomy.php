<?php

namespace MedicalBooking\Infrastructure\DB;

use function MedicalBooking\Helpers\kecb_get_term_name;
use function MedicalBooking\Helpers\kecb_read_json;
use function MedicalBooking\Helpers\kecb_register_taxonomy_json;
use function MedicalBooking\Helpers\kecb_write_error_log;

class Taxonomy
{
    protected static ?self $instance = null;
    protected ?string $taxonomyConfigDir = null;
    protected string $taxonomy_prefix = 'mb_';

    public function __construct()
    {
        $this->taxonomyConfigDir = MB_INFRASTRUCTURE_PATH . 'Config/tax-json/';
        add_action('init', [$this, 'registerTaxonomies'], 5);
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
        $prefix = $this->taxonomy_prefix;
        $files = glob($this->taxonomyConfigDir . '*.json');

        if (!$files) {
            kecb_write_error_log("No taxonomy JSON files found in {$this->taxonomyConfigDir}");
            return;
        }

        foreach ($files as $file) {
           kecb_register_taxonomy_json($file, $prefix, false);
        }
    }

    public function getSpecificTerms(string $taxonomy = 'speciality'): array
    {
        return kecb_get_term_name($this->taxonomy_prefix . $taxonomy, false);
    }

    public function resetTaxonomyTerms(string $taxonomy): void
    {
        $taxonomy = $this->taxonomy_prefix . $taxonomy;
        $terms = get_terms(['taxonomy' => $taxonomy, 'hide_empty' => false]);
        foreach ($terms as $term) {
            wp_delete_term($term->term_id, $taxonomy);
        }
    }
}