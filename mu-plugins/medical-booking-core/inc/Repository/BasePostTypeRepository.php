<?php

namespace MedicalBooking\Repository;
use MedicalBooking\Infrastructure\Cache\CacheManager;
use WP_Query;
abstract class BasePostTypeRepository extends BaseRepository
{
    protected string $post_type;
    protected string $cache_key_prefix = '_'; // Overwrite on Child Class
    protected int $cache_lifetime = WEEK_IN_SECONDS ?? 86400*7;
    protected array $taxonomies;
    protected array $advanced_custom_fields;

    protected function __construct(string $post_type) {
        parent::__construct();
        $this->post_type = $post_type;
        $this->register_cache_hooks();
    }

    abstract protected function format(\WP_Post $post): array;

    protected function getPostTypeName(): ?string
    {
        return $this->post_type ?? null;
    }

    protected function getTaxonomies(): array
    {

        return $this->taxonomies ?? [];
    }

    protected function getPostTermNames(\WP_Post $post): array
    {
        if (empty($this->taxonomies)) {
            return [];
        }

        $result = [];
        foreach ($this->taxonomies as $taxonomy) {
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
        return [
            'name'          => get_the_title($post->ID) ?? '',
            'thumbnail'     => get_the_post_thumbnail_url($post->ID, 'thumbnail') ?? '',
            'description'   => get_the_excerpt($post->ID) ?? '',
            'link'          => get_permalink($post->ID) ?? '',
        ];
    }

    public function getFieldData(\WP_POST $post): array
    {

        // Check Empty field name
        $meta_fields = $this->advanced_custom_fields ?? [];
        if (empty($meta_fields)) {
            return [];
        }

        $fields_data = get_fields($post->ID);

        $data_formatted = [];
        foreach ($meta_fields as $meta_field) {
            $data_formatted[$meta_field] = $fields_data[$meta_field] ?? null;
        }

        return $data_formatted;
    }

    /**
     * Lấy tất cả posts với args tùy chỉnh
     */
    protected function getAll(array $args = []): array {

        $cache_key = $this->cache_key_prefix . 'all_' . md5(serialize($args));

        $cached = CacheManager::get($cache_key);

        if ($cached) {
            return $cached;
        }

        $default_args = [
            'post_type'      => $this->post_type,
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
        $cache_key = $this->cache_key_prefix . 'ids_' . md5(serialize($args));

        $cached = CacheManager::get($cache_key);
        if ($cached) {
            return $cached;
        }

        $default_args = [
            'post_type'      => $this->post_type,
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
    public function getById(int $post_id): ?\WP_Post {
        $post = get_post($post_id);

        if ($post && $post->post_type === $this->post_type && $post->post_status === 'publish') {
            return $post;
        }

        return null;
    }

    protected function clear_cache(): void {
        CacheManager::delete($this->cache_key_prefix . 'all');
        CacheManager::delete($this->cache_key_prefix . 'ids');
        // Clear pattern-based nếu có nhiều cache keys
    }

    protected function register_cache_hooks(): void {
        add_action("save_post_{$this->post_type}", [$this, 'clear_cache']);
        add_action("delete_post", [$this, 'clear_cache']);
    }
}
