<?php get_header(); ?>

<main class="site-main container">
    <?php
    while (have_posts()) : the_post();
        get_template_part('template-parts/content/content', 'single');
    endwhile;
    ?>
</main>

<?php get_footer(); ?>
