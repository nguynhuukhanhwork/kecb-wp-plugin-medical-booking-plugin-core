<?php
// Main sidebar
register_sidebar([
    'name' => __('Main Sidebar', 'medical-booking'),
    'id' => 'main-sidebar',
    'before_widget' => '<div class="widget %2$s">',
    'after_widget' => '</div>',
    'before_title' => '<h3 class="widget-title">',
    'after_title' => '</h3>'
]);

// Footer widget area
register_sidebar([
    'name' => __('Footer Widgets', 'medical-booking'),
    'id' => 'footer-widgets',
    'before_widget' => '<div class="footer-widget %2$s">',
    'after_widget' => '</div>',
    'before_title' => '<h4 class="footer-widget-title">',
    'after_title' => '</h4>'
]);

register_sidebar([
    'name' => __('Header Widgets', 'medical-booking'),
    'id' => 'header-widgets',
    'before_widget' => '<div class="header-widget-%2$s">',
    'after_widget' => '</div>',
    'before_title' => '<h4 class="header-widget-title">',
    'after_title' => '</h4>'
]);

