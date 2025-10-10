<?php get_header(); ?>

<main class="site-main container">
    <h1><?php the_archive_title(); ?></h1>
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <?php get_template_part('template-parts/content/content', 'archive'); ?>
        <?php endwhile; ?>
    <?php else : ?>
        <p><?php _e('No posts found.', 'mytheme'); ?></p>
    <?php endif; ?>
</main>
<?php get_footer(); ?>
