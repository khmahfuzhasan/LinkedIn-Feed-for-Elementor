<?php
/**
 * Plugin Name: LinkedIn Feed for Elementor
 * Plugin URI: https://github.com/khmahfuzhasan/LinkedIn-Feed-for-Elementor
 * Description: Display a LinkedIn-style article feed with customizable layout and styling.
 * Version: 1.0
 * Author: Mahfuz
 * Author URI: https://github.com/khmahfuzhasan/
 */

if (!defined('ABSPATH')) exit;

add_action('elementor/widgets/widgets_registered', function () {
    require_once __DIR__ . '/widget-linkedin-feed.php';
});

add_action('elementor/elements/categories_registered', function ($elements_manager) {
    $elements_manager->add_category('custom-widgets', [
        'title' => 'Custom Widgets',
        'icon'  => 'fa fa-plug',
    ]);
});


add_action('wp_enqueue_scripts', function () {
    wp_register_style('linkedin-feed-style', plugin_dir_url(__FILE__) . 'linkedin-feed-style.css');
});
