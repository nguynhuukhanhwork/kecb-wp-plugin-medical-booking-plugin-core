<?php
/**
 * Template Name: Home Page
 * Description: A custom home page template displaying doctor profiles and registration form.
 */

get_header();

?>

    <div class="home-page">
        <!-- Hero Section -->
        <section class="hero-section">
            <h1><?php _e('Welcome to Our Clinic', 'textdomain'); ?></h1>
            <p><?php _e('Find the best doctors and book your appointment today.', 'textdomain'); ?></p>
        </section>

        <!-- Doctor Profile List Card -->
        <section class="doctor-list">
            <h2><?php _e('Our Doctors', 'textdomain'); ?></h2>
            <?php
            // Gọi template part để hiển thị danh sách bác sĩ
            get_template_part('template-parts/doctor', 'list', ['posts_per_page' => 6]);
            ?>
        </section>

        <!-- Registration Form -->
        <section class="registration-form">
            <h2><?php _e('Book an Appointment', 'textdomain'); ?></h2>
            <?php echo do_shortcode('[registration_form]'); ?>
        </section>
    </div>

<?php get_footer(); ?>