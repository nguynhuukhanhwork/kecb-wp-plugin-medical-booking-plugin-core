<?php
/**
 *
 * @var array $context
 */
?>
<div class="mb-search-container">
    <form class="mb-search-form" method="get" action="">
        <input
            type="search"
            name="keyword"
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
