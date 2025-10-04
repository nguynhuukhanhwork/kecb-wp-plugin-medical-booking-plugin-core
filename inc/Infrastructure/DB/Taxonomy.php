<?php

namespace MedicalBooking\Infrastructure\DB;

use function MedicalBooking\Helpers\kecb_get_term_name;
use function MedicalBooking\Helpers\kecb_read_json;
use function MedicalBooking\Helpers\kecb_write_error_log;

class Taxonomy
{
    protected ?string $taxonomyConfigDir = null;
    protected static ?self $instance = null;
    protected string $taxonomy_prefix = 'mb-';

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
            $config = kecb_read_json($file);

            if (empty($config) || !isset($config['taxonomy'], $config['type'], $config['args'])) {
                kecb_write_error_log("Invalid or empty taxonomy config in " . esc_html($file));
                continue;
            }

            $taxonomy_name = $prefix . $config['taxonomy'];

            if (!taxonomy_exists($taxonomy_name)) {
                register_taxonomy($taxonomy_name, $config['type'], $config['args']);
            }

            if (!empty($config['terms'])) {
                foreach ($config['terms'] as $term) {
                    $name = is_array($term) ? $term['name'] : $term;
                    $slug = is_array($term) && isset($term['slug']) ? $term['slug'] : sanitize_title($name);

                    if (!term_exists($slug, $taxonomy_name)) {
                        wp_insert_term($name, $taxonomy_name, ['slug' => $slug]);
                    }
                }
            }
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