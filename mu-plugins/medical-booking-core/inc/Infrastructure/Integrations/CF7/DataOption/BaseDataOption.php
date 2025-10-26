<?php

namespace MedicalBooking\Infrastructure\Integrations\CF7\DataOption;
abstract class BaseDataOption
{
    protected string $className;
    protected string $postType;

    public function init(): void
    {
        add_filter('wpcf7_form_tag_data_option', [$this, 'getData'], 10, 3);
    }

    public function getData($data, $options, $args)
    {
        if (!in_array($this->className, $options)) {
            return $data;
        }

        return $this->getItems();
    }

    protected function getItems(): array
    {
        $items = [];

        $posts = get_posts([
            'post_type' => $this->postType,
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
            'post_status' => 'publish'
        ]);

        foreach ($posts as $post) {
            $items[] = $post->post_title;
        }

        return $items;
    }
}