<header class="site-header">
    <div class="container">
        <h1 class="site-title">
            <a href="<?php echo esc_url(home_url('/')); ?>">
                <?php bloginfo('name'); ?>
            </a>
        </h1>
        <nav class="main-nav">
            <?php
            wp_nav_menu([
                'theme_location' => 'primary',
                'menu_class'     => 'menu',
                'container'      => false,
            ]);
            ?>
        </nav>
    </div>
</header>
