<?php

namespace MedicalBooking\Repository;

final class ServiceRepository extends BasePostTypeRepository
{
    public static ?self $instance = null;

    private function __construct()
    {
        parent::__construct('service');
    }

    public function get_post_type(): string
    {
        return 'service';
    }

    public function get_all_ids(): array
    {
        return [];
    }

    public function get_all(): array
    {
        return [];
    }

    public function format_post_data(\WP_Post $post): array{

        return [];
    }

}