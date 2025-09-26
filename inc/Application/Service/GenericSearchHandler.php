<?php

namespace MedicalBooking\Application\Service;

use WP_Query;

class GenericSearchHandler
{
    public function __construct() {
        // Đăng ký shortcode
        add_action('init', function() {
            add_shortcode('mb_search_form', [$this, 'render']);
        });
    }

    /**
     * Search posts by post type and keyword
     */
    public function search(string $postType, string $keyword = '', int $limit = 10, string $orderby = 'date', string $order = 'DESC', array $filters = []): array {
        $args = [
            'post_type'      => $postType,
            'post_status'    => 'publish',
            's'              => $keyword,
            'posts_per_page' => $limit,
            'orderby'        => $orderby,
            'order'          => $order,
        ];

        $query = new WP_Query($args);
        return $query->posts;
    }



    /**
     * Render search form shortcode
     */
    public function render($atts) {
        $atts = shortcode_atts([
            'post_type' => 'doctor',
            'limit'     => 10,
        ], $atts);

        ob_start();
        ?>
        <form method="get" class="mb-search-form">
            <input type="text" name="keyword" placeholder="Search..." value="<?php echo esc_attr($_GET['keyword'] ?? ''); ?>">
            <button type="submit">Search</button>
            <input type="hidden"">
        </form>
        <?php

        if (!empty($_GET['keyword'])) {
            $results = $this->search($atts['post_type'], $_GET['keyword'], $atts['limit']);

            echo '<div class="mb-search-results">';
            if ($results) {
                foreach ($results as $post) {
                    $permalink = get_permalink($post->ID);
                    echo '<h3><a href="' . esc_url($permalink) . '">' . esc_html($post->post_title) . '</a></h3>';
                }
            } else {
                echo '<p>No results found.</p>';
            }
            echo '</div>';
        }

        return ob_get_clean();
    }
}