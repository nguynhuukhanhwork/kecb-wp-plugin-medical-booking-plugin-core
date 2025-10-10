// template-parts/layout/footer.php
<footer class="site-footer">
    <div class="container">
        <?php
        // Allow themes/plugins to add footer content
        do_action('medical_booking_footer_start');

        // Default footer content
        if (!has_action('medical_booking_footer_content')) {
            get_template_part('template-parts/footer/default-footer');
        } else {
            do_action('medical_booking_footer_content');
        }

        do_action('medical_booking_footer_end');
        ?>

        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?></p>
        </div>
    </div>
</footer>