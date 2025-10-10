<?php
/**
 * Template Name: Doctor List Page
 */

// Template: SINGLE DOCTOR
get_header('doctor');
?>
<div class="doctor-list">
    <?php
    get_template_part( 'template-parts/doctor/content', 'single' );
    ?>
</div>
<?php get_footer(); ?>
