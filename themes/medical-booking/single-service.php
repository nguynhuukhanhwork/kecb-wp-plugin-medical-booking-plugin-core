<?php
/**
 * Template Name: Doctor List Page
 */

// Template: SINGLE DOCTOR
get_header();
?>
<div class="service-list">
    <?php
    $query = new WP_Query([
        'post_type' => 'service',
        'posts_per_page' => -1
    ]);
    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
            the_post();

        endwhile;
    endif;
    wp_reset_postdata();
    ?>
</div>
<?php get_footer(); ?>
