<?php

namespace MedicalBooking\Infrastructure\WordPress\Registry;

abstract class RegistryBase
{
    protected string $config_dir_path;
    protected string $cache_key;
    protected bool $use_cache = true;
    protected function __construct(string $config_dir_path, string $cache_key) {
        $this->config_dir_path = $config_dir_path;
        $this->cache_key = $cache_key;
    }

    protected function get_cache_data() : array
    {
        if (!$this->use_cache) {
            return [];
        }

        $cached = get_transient($this->cache_key);
        return is_array($cached) ? $cached : [];
    }

    /**
     * Set cache
     */
    protected function set_cache_data(array $data): void {
        if ($this->use_cache && !empty($data)) {
            set_transient($this->cache_key, $data, WEEK_IN_SECONDS);
        }
    }

    /**
     * Delete cache
     */
    protected function delete_cache() : void {
        delete_transient($this->cache_key);
    }

    /**
     * Get all file in folder
     * @return array
     */
    protected function load_all_json(): array {
        return kecb_get_all_files_dir($this->config_dir_path);
    }

    /**
     * Get configs from JSON file config
     * @return array
     */
    protected function get_configs(): array {
        // Try from cache
        $cached = $this->get_cache_data();
        if (!empty($cached)) {
            return $cached;
        }

        // Load file config JSON
        $all_files = $this->load_all_json();

        if (empty($all_files)) {
            error_log('[CPT Registry] No JSON files found.');
            return [];
        }

        // Read and write configs into array
        $configs = [];
        foreach ($all_files as $file) {
            $configs[] = kecb_read_json($file);
        }

        // Set cache
        $this->set_cache_data($configs);

        return $configs;
    }

    /** Register CTP/ACF/Taxonomies */
    abstract public function register();
}