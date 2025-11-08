<?php

namespace TravelBooking\Infrastructure\WordPress\Registry;

use TravelBooking\Infrastructure\Cache\CacheManager;

abstract class RegistryBase
{
    protected string $cache_key_prefix = 'travel_booking_';
    protected function __construct() {
    }
    abstract protected static function defineCacheKey(): string;
    abstract protected function getConfigPath(): string;

    protected function getCacheKey(): string
    {
        return $this->cache_key_prefix . $this->defineCacheKey();
    }

    protected function getCacheData() : array
    {
        $cache_key = $this->getCacheKey();
         $cached = get_transient($cache_key);
        return is_array($cached) ? $cached : [];
    }

    /**
     * Set cache
     */
    protected function setCacheData(array $data): void {
        CacheManager::set(static::defineCacheKey(), $data);
    }

    /**
     * Delete cache
     */
    protected function deleteCacheData() : void {
        $cache_key = $this->getCacheKey();
        delete_transient($cache_key);
    }

    /**
     * Get all file in folder
     * @return array
     */
    protected function loadAllJsonConfig(): array {
        $config_dir_path = $this->getConfigPath();
        return kecb_get_all_files_dir($config_dir_path);
    }

    /**
     * Get configs from JSON file config
     * @return array
     */
    protected function getConfigs(): array {
        // Try from cache
        $cached = $this->getCacheData();
        if (!empty($cached)) {
            return $cached;
        }

        // Load file config JSON
        $all_files = $this->loadAllJsonConfig();

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
        $this->setCacheData($configs);

        return $configs;
    }

    abstract public static function getInstance();

    /** Register CTP/ACF/Taxonomies */
    abstract protected function register();
}