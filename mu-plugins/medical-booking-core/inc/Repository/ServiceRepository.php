<?php

namespace MedicalBooking\Repository;

final class ServiceRepository extends BasePostTypeRepository
{
    private static ?self $instance = null;
    protected string $cache_key_prefix = 'service_';
    protected int $cache_lifetime = WEEK_IN_SECONDS ?? 86400*7;

    private function __construct()
    {
        parent::__construct('service');
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getPostTypeName(): ?string
    {
        return parent::getPostTypeName() ?? 'service';
    }

    public function format(\WP_Post $post): array
    {
        // ✅ Load TẤT CẢ ACF fields một lần
        $fields = get_fields($post->ID);

        // ✅ Validate và set defaults
        $cost = isset($fields['service_cost']) ? (float) $fields['service_cost'] : 0;
        $time = isset($fields['service_time']) ? (int) $fields['service_time'] : 0;
        $shortDesc = $fields['service_short_description'] ?? '';
        $description = $fields['service_description'] ?? '';
        $doctorIds = $fields['service_doctor'] ?? [];

        $formatted = [
            'id' => $post->ID,
            'name' => get_the_title($post),
            'slug' => $post->post_name,
            'permalink' => get_permalink($post),
            'thumbnail' => get_the_post_thumbnail_url($post->ID, 'medium') ?: '',
            'cost' => $cost,
            'time' => $time,
            'short_description' => $shortDesc,
            'description' => apply_filters('the_content', $description),

        ];

        return $formatted;
    }


}