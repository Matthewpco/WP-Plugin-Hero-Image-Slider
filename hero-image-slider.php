<?php
/*
Plugin Name: Hero Image Slider
Plugin URI: https://github.com/Matthewpco/WP-Plugin-Hero-Image-Slider
Description: Adds a submenu under tools called Hero Image Slider that takes multiple 
image URLs and a title for each one.
Version: 1.1.0
Author: Gary Matthew Payne
Author URI: https://wpwebdevelopment.com/
License: GPL2
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

add_action('admin_menu', 'hero_image_slider_menu');

function hero_image_slider_menu() {
    add_submenu_page('tools.php', 'Hero Image Slider', 'Hero Image Slider', 'manage_options', 'hero-image-slider', 'hero_image_slider_options');
}

function hero_image_slider_options() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }
    echo '<div class="wrap">';
    echo '<h1>Hero Image Slider</h1>';
    // Add form to enter image URLs and titles here
    echo '<form method="post" action="options.php">';
    settings_fields('hero_image_slider_options');
    do_settings_sections('hero-image-slider');
    submit_button();
    echo '</form>';
    echo '</div>';
}

add_action('admin_init', 'hero_image_slider_settings');

function hero_image_slider_settings() {
    register_setting('hero_image_slider_options', 'hero_image_slider_images', 'hero_image_slider_images_sanitize');
    add_settings_section('hero_image_slider_section', 'Images', 'hero_image_slider_section_callback', 'hero-image-slider');
    add_settings_field('hero_image_slider_images', 'Images', 'hero_image_slider_images_callback', 'hero-image-slider', 'hero_image_slider_section');
}

function hero_image_slider_section_callback() {
    echo '<p>Add images and titles for the Hero Image Slider.</p>';
}

function hero_image_slider_images_callback() {
    $images = get_option('hero_image_slider_images');
    if (!is_array($images)) {
        $images = array();
    }
    foreach ($images as $image) {
        echo '<p><input type="text" name="hero_image_slider_images[url][]" value="' . esc_attr($image['url']) . '" placeholder="Image URL"> <input type="text" name="hero_image_slider_images[title][]" value="' . esc_attr($image['title']) . '" placeholder="Title"></p>';
    }
    echo '<p><input type="text" name="hero_image_slider_images[url][]" placeholder="Image URL"> <input type="text" name="hero_image_slider_images[title][]" placeholder="Title"></p>';
}

function hero_image_slider_images_sanitize($input) {
    $output = array();
    if (isset($input['url']) && is_array($input['url'])) {
        foreach ($input['url'] as $key => $url) {
            if (!empty($url)) {
                $output[] = array(
                    'url' => sanitize_text_field($url),
                    'title' => sanitize_text_field($input['title'][$key])
                );
            }
        }
    }
    return $output;
}

add_shortcode('hero-image-slider', 'hero_image_slider_shortcode');

function hero_image_slider_shortcode($atts) {
    wp_enqueue_style('hero-image-slider-style', plugin_dir_url(__FILE__) . 'css/style.css');
    wp_enqueue_script('hero-image-slider-script', plugin_dir_url(__FILE__) . 'js/script.js', array('jquery'), false, true);
    
    $images = get_option('hero_image_slider_images');
    if (!is_array($images)) {
        return '';
    }
    
    $output = '<div class="hero-image-slider">';
    
    foreach ($images as $image) {
        $output .= '<div class="hero-image-slide"><img src="' . esc_attr($image['url']) . '" alt="' . esc_attr($image['title']) . '"> <span class="hero-image-title">' . esc_html($image['title']) . '</span></div>';
    }
    
    $output .= '<button class="prev">&#10094;</button><button class="next">&#10095;</button></div>';
    
    return $output;
}