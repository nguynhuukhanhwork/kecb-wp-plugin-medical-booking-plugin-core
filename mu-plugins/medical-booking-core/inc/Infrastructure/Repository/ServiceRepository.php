<?php

namespace MedicalBooking\Infrastructure\Repository;

use MedicalBooking\Infrastructure\DB\ConfigDb;
use MedicalBooking\Infrastructure\Config\ConfigReader;
use function MedicalBooking\Helpers\kecb_register_acf_field_json;
use function MedicalBooking\Helpers\kecb_register_post_type_json;
use wpdb;

class ServiceRepository
{
    /** @var string CPT config file name */
    public const CPT_CONFIG_FILE = 'service-cpt.json';
    
    /** @var string|null File config json path of post type 'service' */
    protected ?string $cptConfigFilePath = null;
    /** @var string|null File config json path of fields for post type 'service' */
    protected ?string $acfConfigFilePath;

    protected const CACHE_GROUP = 'medical_booking_services';
    protected const CACHE_ALL_SERVICE_IDS = 'all_services';
    protected const CACHE_TIME = HOUR_IN_SECONDS;

    /**
     * Lấy post type name từ config (dynamic)
     * 
     * @return string
     */
    private static function getPostType(): string
    {
        return ConfigReader::getPostTypeFromJson(
            MB_INFRASTRUCTURE_PATH . 'Config/cpt-json/' . self::CPT_CONFIG_FILE
        );
    }

    public function __construct()
    {
        $this->cptConfigFilePath = MB_INFRASTRUCTURE_PATH . 'Config/cpt-json/' . self::CPT_CONFIG_FILE;
        $this->acfConfigFilePath = MB_INFRASTRUCTURE_PATH . 'Config/acf-json/service-fields.json';
        add_action('init', [$this, 'registerCpt']);
        add_action('init', [$this, 'registerAcfFields']);

        $config = ConfigDb::get_instance();
    }

    /**
     * Register Post Type 'service' with JSON config
     *
     * @return void
     */
    public function registerCpt(): void
    {
        kecb_register_post_type_json($this->cptConfigFilePath);
    }

    /**
     * Register ACF Fields  for post type 'service'
     * @return void
     */
    public function registerAcfFields(): void
    {
        kecb_register_acf_field_json($this->acfConfigFilePath);
    }

    public function getById($id): array{


        return [];
    }

    public function getAllId(array $ids): array {
        $cache_key = self::CACHE_ALL_SERVICE_IDS;
        $cache = wp_cache_get($cache_key, self::CACHE_GROUP);

        if (false !== $cache) {
            return $cache;
        }

        $args = [
            'post_type' => self::getPostType(),
            'fields' => 'ids',
        ];
        return [];
    }

    public function searchByName(string $name): array
    {
        return [];
    }
}