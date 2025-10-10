<footer class="site-footer">
    <?php do_action('medical_booking_before_footer'); ?>

    <div class="container">
        <?php if (is_active_sidebar('footer-widgets')): ?>
            <div class="footer-widgets">
                <?php dynamic_sidebar('footer-widgets'); ?>
            </div>
        <?php else: ?>
            <!-- Fallback content -->
            <div class="row">
                <h2><?php echo get_theme_mod('footer_title', 'This is Footer'); ?></h2>
            </div>
        <?php endif; ?>

        <?php get_template_part('template-parts/footer/footer-nav'); ?>

        <div class="footer-bottom">
            <?php do_action('medical_booking_footer_bottom'); ?>
            <p>&copy; <?php echo date('Y') . ' ' . get_bloginfo('name'); ?></p>
        </div>
    </div>

    <?php do_action('medical_booking_after_footer'); ?>
</footer>