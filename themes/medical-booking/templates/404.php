<div class="error-404-container">
    <?php if (function_exists('elementor_theme_do_location') && elementor_theme_do_location('single')) : ?>
        <!-- Elementor handles this -->
    <?php else : ?>
        <div class="container">
            <h1>Không tìm thấy trang</h1>
            <p>Xin lỗi, trang bạn tìm kiếm không tồn tại.</p>
            <?php get_search_form(); ?>
        </div>
    <?php endif; ?>
</div>
