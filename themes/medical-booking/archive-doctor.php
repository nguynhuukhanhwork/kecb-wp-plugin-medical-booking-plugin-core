<?php get_header(); ?>
<main class="archive-doctor">
    <h1>Tất cả bác sĩ</h1>
    <div class="doctor-grid">
        <?php
        get_template_part('template-parts/doctor/content', 'card');
        ?>
    </div>
</main>

<?php get_footer(); ?>
