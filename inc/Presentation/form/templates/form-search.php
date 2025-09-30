<?php
/**
 * Search form template for MedicalBooking plugin.
 * Displays a search form for doctors or services, integrated with the [mb_search_form] shortcode.
 *
 * @since 1.0.0
 * @package MedicalBooking
 * @author Your Name <your.email@example.com>
 * @var array $context Form configuration data (post_type, placeholder, button_text).
 */
?>
<div class="mb-search-container">
    <form class="mb-search-form" method="get" action="">
        <label for="search-system"></label>
        <input
                type="search"
                name="keyword"
                id="search-system"
                placeholder="<?php echo esc_attr($context['placeholder']); ?>"
                required
        />

        <button type="submit">
            <?php echo esc_html($context['button_text']); ?>
        </button>
    </form>

    <div class="mb-search-loading" style="display:none;">
        <p>Đang tìm kiếm...</p>
    </div>

    <div class="mb-search-results"></div>
</div>
