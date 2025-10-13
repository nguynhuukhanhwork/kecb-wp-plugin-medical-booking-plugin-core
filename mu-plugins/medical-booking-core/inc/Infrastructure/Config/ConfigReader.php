<?php

namespace MedicalBooking\Infrastructure\Config;

use function MedicalBooking\Helpers\kecb_read_json;
use function MedicalBooking\Helpers\kecb_write_error_log;

/**
 * ConfigReader - Utility class để đọc cấu hình từ JSON files
 * 
 * Single source of truth cho việc đọc CPT, Taxonomy, và ACF config
 */
final class ConfigReader
{
    /**
     * Cache cho các config đã đọc để tránh đọc file nhiều lần
     */
    private static array $configCache = [];

    /**
     * Lấy post type từ JSON config file
     * 
     * @param string $configFile Đường dẫn đến file JSON
     * @return string Post type name
     */
    public static function getPostTypeFromJson(string $configFile): string
    {
        $cacheKey = 'post_type_' . md5($configFile);
        
        if (isset(self::$configCache[$cacheKey])) {
            return self::$configCache[$cacheKey];
        }

        $config = kecb_read_json($configFile);
        $postType = $config['post_type'] ?? '';

        if (empty($postType)) {
            kecb_write_error_log("Post type not found in config file: {$configFile}");
            return '';
        }

        self::$configCache[$cacheKey] = $postType;
        return $postType;
    }

    /**
     * Lấy taxonomy name từ JSON config file
     * 
     * @param string $configFile Đường dẫn đến file JSON
     * @return string Taxonomy name
     */
    public static function getTaxonomyFromJson(string $configFile): string
    {
        $cacheKey = 'taxonomy_' . md5($configFile);
        
        if (isset(self::$configCache[$cacheKey])) {
            return self::$configCache[$cacheKey];
        }

        $config = kecb_read_json($configFile);
        $taxonomy = $config['taxonomy'] ?? '';

        if (empty($taxonomy)) {
            kecb_write_error_log("Taxonomy not found in config file: {$configFile}");
            return '';
        }

        self::$configCache[$cacheKey] = $taxonomy;
        return $taxonomy;
    }

    /**
     * Lấy toàn bộ config từ JSON file
     * 
     * @param string $configFile Đường dẫn đến file JSON
     * @return array Config array
     */
    public static function getConfigFromJson(string $configFile): array
    {
        $cacheKey = 'config_' . md5($configFile);
        
        if (isset(self::$configCache[$cacheKey])) {
            return self::$configCache[$cacheKey];
        }

        $config = kecb_read_json($configFile);
        self::$configCache[$cacheKey] = $config;
        
        return $config;
    }

    /**
     * Lấy tất cả post types từ các CPT config files
     * 
     * @return array Array với key là entity name và value là post type
     */
    public static function getAllPostTypes(): array
    {
        $cacheKey = 'all_post_types';
        
        if (isset(self::$configCache[$cacheKey])) {
            return self::$configCache[$cacheKey];
        }

        $postTypes = [];
        $configDir = MB_INFRASTRUCTURE_PATH . 'Config/cpt-json/';

        $cptFiles = glob($configDir . '*-cpt.json');

        foreach ($cptFiles as $filePath) {
            $entity = basename($filePath, '-cpt.json');
            $postType = self::getPostTypeFromJson($filePath);
            if ($postType) {
                $postTypes[$entity] = $postType;
            }
        }

        self::$configCache[$cacheKey] = $postTypes;
        return $postTypes;
    }

    /**
     * Lấy tất cả taxonomies từ các taxonomy config files
     * 
     * @return array Array với key là taxonomy name và value là config
     */
    public static function getAllTaxonomies(): array
    {
        $cacheKey = 'all_taxonomies';
        
        if (isset(self::$configCache[$cacheKey])) {
            return self::$configCache[$cacheKey];
        }

        $taxonomies = [];
        $configDir = MB_INFRASTRUCTURE_PATH . 'Config/tax-json/';

        $taxFiles = glob($configDir . '*.json');

        foreach ($taxFiles as $taxName => $filePath) {
            if (file_exists($filePath)) {
                $taxonomy = self::getTaxonomyFromJson($filePath);
                if (!empty($taxonomy)) {
                    $taxonomies[$taxName] = $taxonomy;
                }
            }
        }

        self::$configCache[$cacheKey] = $taxonomies;
        return $taxonomies;
    }

    /**
     * Lấy post type cho một entity cụ thể
     * 
     * @param string $entity Entity name (doctor, service, patient)
     * @return string Post type name
     */
    public static function getPostTypeForEntity(string $entity): string
    {
        $postTypes = self::getAllPostTypes();
        return $postTypes[$entity] ?? '';
    }

    /**
     * Lấy taxonomy name cho một taxonomy cụ thể
     * 
     * @param string $taxonomyKey Taxonomy key (degree, gender, etc.)
     * @return string Taxonomy name
     */
    public static function getTaxonomyName(string $taxonomyKey): string
    {
        $taxonomies = self::getAllTaxonomies();
        return $taxonomies[$taxonomyKey] ?? '';
    }

    /**
     * Clear config cache (useful for testing hoặc khi config thay đổi)
     */
    public static function clearCache(): void
    {
        self::$configCache = [];
    }
}
