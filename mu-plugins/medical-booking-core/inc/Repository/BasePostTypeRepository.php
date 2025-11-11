<?php

namespace TravelBooking\Repository;
use TravelBooking\Infrastructure\Cache\CacheManager;
use WP_Query;
abstract class BasePostTypeRepository
{
    protected int $cache_lifetime = WEEK_IN_SECONDS ?? 86400*7;
    abstract static public function getInstance();
    abstract static function DEFINE_CACHE_KEY_PREFIX(): string;
    abstract static function POST_TYPE(): string;
    abstract static function FIELDS(): array;
    abstract static function TAXONOMY(): array;

    protected function __construct() {}
    protected function getPostTypeName(): ?string
    {
        return static::POST_TYPE() ?? null;
    }

    protected function getTaxonomies(): array
    {
        return static::TAXONOMY() ?? [];
    }

    protected function getFields(): ?array
    {
        return static::FIELDS() ?? null;
    }

    protected function getTermsByTaxonomy(\WP_Post $post): array
    {
        $taxonomies = $this->getTaxonomies();
        if (empty($taxonomies)) {
            return [];
        }

        $result = [];
        foreach ($taxonomies as $taxonomy) {
            $terms = wp_get_post_terms($post->ID, $taxonomy, ['fields' => 'names']);
            $result[$taxonomy] = $terms ?: [];
        }

        return $result;
    }

    /**
     * Get basic post information: name, thumbnail image url, description, post type permalink
     * @param \WP_POST $post
     * @return array
     */
    protected function getBasicInfo(\WP_POST $post): array {
        $post_id = $post->ID;
        return [
            'name'          => get_the_title($post_id) ?? '',
            'thumbnail'     => get_the_post_thumbnail_url($post_id, 'thumbnail') ?? '',
            'description'   => get_the_excerpt($post_id) ?? '',
            'link'          => get_permalink($post_id) ?? '',
        ];
    }

    protected function getFieldData(\WP_POST $post): array
    {

        $fields = static::FIELDS();     // Get all field
        $post_id = $post->ID;           // Get post ID

        $data_format = [];

        foreach ($fields as $field) {
            // format to ['field_name', 'data']
            $data_format[$field] = get_post_meta($post_id, $field, true);
        }

        return $data_format;
    }

    protected function getPermalinkNameMap(): array
    {
        // Get Cached
        $cache_key = static::DEFINE_CACHE_KEY_PREFIX() . 'all_permalink_name_' . static::POST_TYPE();

        $cached = CacheManager::get($cache_key);

        if ($cached) {
            return $cached;
        }

        $posts = $this->getAll();

        // Format Post [ [permalink => title], [permalink => title] ]
        $data_format = array_map(function ($post) {
            $tour_id = $post->ID;
            $permalink = get_permalink($tour_id);
            $title = get_the_title($tour_id);

            return [$permalink => $title];

        }, $posts);

        // Set Cache
        CacheManager::set($cache_key, $data_format);

        return $data_format;
    }
    /**
     * Lấy tất cả posts với args tùy chỉnh
     * @param array $args
     * @return array là Post ID
     */
    protected function getAll(array $args = []): array {

        $cache_key = static::DEFINE_CACHE_KEY_PREFIX() . 'all_' . md5(serialize($args));

        $cached = CacheManager::get($cache_key);

        if ($cached) {
            return $cached;
        }

        $default_args = [
            'post_type'      => static::POST_TYPE(),
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'orderby'        => 'date',
            'order'          => 'DESC',
            'no_found_rows'  => true,
            'update_post_meta_cache' => false,
            'update_post_term_cache' => false,
        ];

        $merged_args = array_merge($default_args, $args); // Override args

        $query = new WP_Query($merged_args);
        $posts = $query->posts;

        wp_reset_postdata(); // Reset sau khi lấy data

        CacheManager::set($cache_key, $posts, $this->cache_lifetime);

        return $posts;
    }

    /**
     * Get all post type data child class
     * @param array $args
     * @return array
     */
    protected function getAllIds(array $args = []): array {
        $cache_key = static::DEFINE_CACHE_KEY_PREFIX() . 'all_ids';

        $cached = CacheManager::get($cache_key);
        if ($cached) {
            return $cached;
        }

        $post_type = static::POST_TYPE();

        $default_args = [
            'post_type'      => $post_type,
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'orderby'        => 'date',
            'order'          => 'DESC',
            'fields'         => 'ids',
            'no_found_rows'  => true,
            'update_post_meta_cache' => false,
            'update_post_term_cache' => false,
        ];

        $merged_args = array_merge($default_args, $args); // Override args

        $query = new WP_Query($merged_args);
        $posts = $query->posts;

        wp_reset_postdata(); // Reset sau khi lấy data

        CacheManager::set($cache_key, $posts, $this->cache_lifetime);

        return $posts;
    }

    /**
     * Lấy post theo ID
     */
    protected function getById(int $post_id): ?\WP_Post {
        $post = get_post($post_id);

        if ($post && $post->post_type === static::POST_TYPE() && $post->post_status === 'publish') {
            return $post;
        }

        return null;
    }

    protected function mapToEntity(\WP_Post $post): array
    {
        // Get 3 Array data
        $basic_info = $this->getBasicInfo($post);
        $field_data = $this->getFieldData($post);
        $term_data = $this->getTermsByTaxonomy($post);

        return array_merge($basic_info, $field_data, $term_data) ?? [];
    }

    protected function getEntity(\WP_Post $post): array
    {
        $cache_key = static::DEFINE_CACHE_KEY_PREFIX() . "entity_$post->ID";

        $cached = CacheManager::get($cache_key);

        if ($cached) {
            return $cached;
        }

        $entity = $this->mapToEntity($post);

        CacheManager::set($cache_key, $entity, $this->cache_lifetime);

        return $entity;
    }

    protected function getAllEntity(): array {

        // Try get Cache
        $cache_key = static::DEFINE_CACHE_KEY_PREFIX() . 'all_entity';

        $cached = CacheManager::get($cache_key);
        if ($cached) {
            return $cached;
        }

        // Get all post ids
        $post_ids = $this->getAllIds();

        if (empty($post_ids)) {
            return [];
        }

        // Convert to Entity
        $result = [];
        foreach ($post_ids as $post_id) {
            $post = $this->getById($post_id);
            $result[] = $this->getEntity($post);
        }

        CacheManager::set($cache_key, $result, $this->cache_lifetime);

        return $result;
    }

    protected function getAllNames(): array {
        $cache_key = static::DEFINE_CACHE_KEY_PREFIX() . 'all_names';
        $cached = CacheManager::get($cache_key);

        if ($cached) {
            return $cached;
        }

        $all_posts = $this->getAll();

        if (empty($all_posts)) {
            return [];
        }

        $result = [];

        foreach ($all_posts as $post) {
            $result[] = $post->post_title;
        }

        CacheManager::set($cache_key, $result, $this->cache_lifetime);

        return $result;
    }

    /**
     * Get all Term Name of Taxonomy, data ['term_id' => 'term_name']
     * @param string $taxonomy_name
     * @return array
     */
    protected function getTermList(string $taxonomy_name): array {

        // Try get Cache
        $cache_key = 'term_list_' . $taxonomy_name;
        $cached = CacheManager::get($cache_key);

        if ($cached) {
            return $cached;
        }

        // Get Term
        $terms = get_terms([
                'taxonomy' => $taxonomy_name,
                'hide_empty' => false,
                'orderby' => 'name',
                'order' => 'ASC',
                'fields' => 'id=>name'
            ]
        );

        // Check Error
        if (is_wp_error($terms)) {
            CacheManager::set($cache_key, $terms, $this->cache_lifetime);
            return [];
        }

        // Set Caching and return data
        CacheManager::set($cache_key, $terms, $this->cache_lifetime);
        return $terms;
    }
}