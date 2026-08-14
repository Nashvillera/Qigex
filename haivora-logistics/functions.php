<?php
/**
 * Haivora Logistics functions and definitions
 *
 * @package Haivora_Logistics
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Theme Constants
 */
define('HAIVORA_LOGISTICS_VERSION', '1.0.0');
define('HAIVORA_LOGISTICS_DIR', get_template_directory());
define('HAIVORA_LOGISTICS_URI', get_template_directory_uri());

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
if (!function_exists('haivora_logistics_setup')) :
    function haivora_logistics_setup() {
        /*
         * Make theme available for translation.
         */
        load_theme_textdomain('haivora-logistics', HAIVORA_LOGISTICS_DIR . '/languages');

        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');

        /*
         * Let WordPress manage the document title.
         */
        add_theme_support('title-tag');

        /*
         * Enable support for Post Thumbnails on posts and pages.
         */
        add_theme_support('post-thumbnails');
        set_post_thumbnail_size(800, 500, true);

        // Register Navigation Menus
        register_nav_menus(array(
            'primary' => __('Primary Navigation', 'haivora-logistics'),
            'footer'  => __('Footer Navigation', 'haivora-logistics'),
        ));

        /*
         * Switch default core markup to output valid HTML5.
         */
        add_theme_support('html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        ));

        // Set up the WordPress core custom logo feature.
        add_theme_support('custom-logo', array(
            'height'      => 80,
            'width'       => 280,
            'flex-height' => true,
            'flex-width'  => true,
            'header-text' => array('site-title', 'site-description'),
        ));

        // Add theme support for selective refresh for widgets.
        add_theme_support('customize-selective-refresh-widgets');

        // Add support for Block Styles and Gutenberg full-width alignments.
        add_theme_support('wp-block-styles');
        add_theme_support('align-wide');
        add_theme_support('editor-styles');
    }
endif;
add_action('after_setup_theme', 'haivora_logistics_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 */
function haivora_logistics_content_width() {
    $GLOBALS['content_width'] = apply_filters('haivora_logistics_content_width', 1200);
}
add_action('after_setup_theme', 'haivora_logistics_content_width', 0);

/**
 * Enqueue scripts and styles.
 */
function haivora_logistics_scripts() {
    // Theme stylesheet.
    wp_enqueue_style('haivora-logistics-style', get_stylesheet_uri(), array(), HAIVORA_LOGISTICS_VERSION);

    // Main assets stylesheet.
    if (file_exists(HAIVORA_LOGISTICS_DIR . '/assets/css/main.css')) {
        wp_enqueue_style('haivora-logistics-main', HAIVORA_LOGISTICS_URI . '/assets/css/main.css', array(), HAIVORA_LOGISTICS_VERSION);
    }

    // Navigation Script.
    if (file_exists(HAIVORA_LOGISTICS_DIR . '/assets/js/navigation.js')) {
        wp_enqueue_script('haivora-logistics-navigation', HAIVORA_LOGISTICS_URI . '/assets/js/navigation.js', array(), HAIVORA_LOGISTICS_VERSION, true);
    }

    // Main Script.
    if (file_exists(HAIVORA_LOGISTICS_DIR . '/assets/js/main.js')) {
        wp_enqueue_script('haivora-logistics-main', HAIVORA_LOGISTICS_URI . '/assets/js/main.js', array(), HAIVORA_LOGISTICS_VERSION, true);
    }

    // Phase 1 Tracking Widget Interactive Preview.
    if (file_exists(HAIVORA_LOGISTICS_DIR . '/assets/js/tracking-preview.js')) {
        wp_enqueue_script('haivora-logistics-tracking-preview', HAIVORA_LOGISTICS_URI . '/assets/js/tracking-preview.js', array(), HAIVORA_LOGISTICS_VERSION, true);
    }

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'haivora_logistics_scripts');

/**
 * Customizer additions.
 */
if (file_exists(HAIVORA_LOGISTICS_DIR . '/inc/customizer.php')) {
    require_once HAIVORA_LOGISTICS_DIR . '/inc/customizer.php';
}

/**
 * Custom template tags for this theme.
 */
if (file_exists(HAIVORA_LOGISTICS_DIR . '/inc/template-tags.php')) {
    require_once HAIVORA_LOGISTICS_DIR . '/inc/template-tags.php';
}

/**
 * Phase 4: Shipment Custom Post Type & Admin System
 */
if (file_exists(HAIVORA_LOGISTICS_DIR . '/inc/cpt-shipment.php')) {
    require_once HAIVORA_LOGISTICS_DIR . '/inc/cpt-shipment.php';
}

if (file_exists(HAIVORA_LOGISTICS_DIR . '/inc/shipment-admin.php')) {
    require_once HAIVORA_LOGISTICS_DIR . '/inc/shipment-admin.php';
}

/**
 * Helper Functions for Branding Defaults
 */
function haivora_logistics_company_name() {
    return get_theme_mod('haivora_company_name', 'Qidex Express LOGISTICS');
}

function haivora_logistics_phone() {
    return get_theme_mod('haivora_phone', '+1 (800) 555-QIDEX');
}

function haivora_logistics_email() {
    return get_theme_mod('haivora_email', 'support@qidexexpress.com');
}

function haivora_logistics_address() {
    return get_theme_mod('haivora_address', '100 Global Trade Parkway, Logistics Hub, NY 10001');
}
