<?php

namespace MedicalBooking\Repository;
use WP_Query;
abstract class BasePostTypeRepository extends BaseRepository
{
    protected string $post_type;
    protected string $cache_group = 'posts'; // Để override trong child

    protected function __construct(string $post_type) { // ← PROTECTED
        parent::__construct();
        $this->post_type = $post_type;
    }

    /**
     * Lấy tất cả posts với args tùy chỉnh
     */
    protected function get_posts(array $args = []): array {
        $default_args = [
            'post_type'      => $this->post_type,
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'orderby'        => 'date',
            'order'          => 'DESC',
        ];

        $merged_args = array_merge($default_args, $args); // Override args

        $query = new WP_Query($merged_args);
        $posts = $query->posts;

        wp_reset_postdata(); // Reset sau khi lấy data

        return $posts;
    }

    /**
     * Lấy tất cả posts với args tùy chỉnh
     */
    protected function get_post_ids(array $args = []): array {
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

        return $posts;
    }

    /**
     * Lấy 1 post mới nhất
     */
    protected function get_latest_post(): ?\WP_Post {
        $posts = $this->get_posts(['posts_per_page' => 1]);
        return !empty($posts) ? $posts[0] : null;
    }

    /**
     * Lấy post theo ID
     */
    protected function get_post_by_id(int $post_id): ?\WP_Post {
        $post = get_post($post_id);

        if ($post && $post->post_type === $this->post_type && $post->post_status === 'publish') {
            return $post;
        }

        return null;
    }

    /**
     * Lấy formatted data với cache
     */
    public function get_all_formatted(array $args = [], int $cache_time = 3600): array {
        $cache_key = $this->generate_cache_key('all', $args);

        $cached = get_transient($cache_key);
        if ($cached !== false) {
            return $cached;
        }

        $posts = $this->get_posts($args);
        $formatted = array_map([$this, 'format_post_data'], $posts);

        set_transient($cache_key, $formatted, $cache_time);

        return $formatted;
    }

    /**
     * Generate cache key dựa trên args
     */
    protected function generate_cache_key(string $prefix, array $args = []): string {
        $key = $this->post_type . '_' . $prefix;
        if (!empty($args)) {
            $key .= '_' . md5(serialize($args));
        }
        return $key;
    }

    /**
     * Clear cache của post type này
     */
    public function clear_cache(): void {
        // WordPress không có cách clear transient theo pattern
        // Nên dùng object cache hoặc tự implement cache manager
        delete_transient($this->post_type . '_all');
    }
}
