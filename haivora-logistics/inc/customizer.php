<?php
/**
 * Haivora Logistics Theme Customizer
 *
 * @package Haivora_Logistics
 */

if (!defined('ABSPATH')) {
    exit;
}

function haivora_logistics_customize_register($wp_customize) {
    // -------------------------------------------------------------
    // Section: Company Info & Branding Settings
    // -------------------------------------------------------------
    $wp_customize->add_section('haivora_company_section', array(
        'title'    => __('Logistics Company Info', 'haivora-logistics'),
        'priority' => 30,
    ));

    // Company Name
    $wp_customize->add_setting('haivora_company_name', array(
        'default'           => 'Qidex Express LOGISTICS',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control('haivora_company_name', array(
        'label'    => __('Company Name', 'haivora-logistics'),
        'section'  => 'haivora_company_section',
        'type'     => 'text',
    ));

    // Phone Number
    $wp_customize->add_setting('haivora_phone', array(
        'default'           => '+1 (800) 555-QIDEX',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('haivora_phone', array(
        'label'   => __('Phone / Hotline', 'haivora-logistics'),
        'section' => 'haivora_company_section',
        'type'    => 'text',
    ));

    // Email
    $wp_customize->add_setting('haivora_email', array(
        'default'           => 'support@qidexexpress.com',
        'sanitize_callback' => 'sanitize_email',
    ));
    $wp_customize->add_control('haivora_email', array(
        'label'   => __('Support Email', 'haivora-logistics'),
        'section' => 'haivora_company_section',
        'type'    => 'text',
    ));

    // Address
    $wp_customize->add_setting('haivora_address', array(
        'default'           => '100 Global Trade Parkway, Logistics Hub, NY 10001',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('haivora_address', array(
        'label'   => __('Corporate Address', 'haivora-logistics'),
        'section' => 'haivora_company_section',
        'type'    => 'text',
    ));

    // WhatsApp Number
    $wp_customize->add_setting('haivora_whatsapp', array(
        'default'           => '+18005557433',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('haivora_whatsapp', array(
        'label'   => __('WhatsApp Direct Contact (with country code)', 'haivora-logistics'),
        'section' => 'haivora_company_section',
        'type'    => 'text',
    ));

    // -------------------------------------------------------------
    // Section: Hero Section Settings
    // -------------------------------------------------------------
    $wp_customize->add_section('haivora_hero_section', array(
        'title'    => __('Homepage Hero Settings', 'haivora-logistics'),
        'priority' => 35,
    ));

    // Hero Headline
    $wp_customize->add_setting('haivora_hero_headline', array(
        'default'           => 'Global Shipping. Smarter Logistics.',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('haivora_hero_headline', array(
        'label'   => __('Hero Headline', 'haivora-logistics'),
        'section' => 'haivora_hero_section',
        'type'    => 'text',
    ));

    // Hero Subtext
    $wp_customize->add_setting('haivora_hero_subtext', array(
        'default'           => 'Reliable international freight forwarding, express courier, and end-to-end supply chain management for global enterprises.',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control('haivora_hero_subtext', array(
        'label'   => __('Hero Subtext', 'haivora-logistics'),
        'section' => 'haivora_hero_section',
        'type'    => 'textarea',
    ));

    // -------------------------------------------------------------
    // Section: Theme Colors
    // -------------------------------------------------------------
    $wp_customize->add_setting('haivora_primary_color', array(
        'default'           => '#0F2137',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'haivora_primary_color', array(
        'label'    => __('Primary Navy Color', 'haivora-logistics'),
        'section'  => 'colors',
        'settings' => 'haivora_primary_color',
    )));

    $wp_customize->add_setting('haivora_secondary_color', array(
        'default'           => '#FF9F00',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'haivora_secondary_color', array(
        'label'    => __('Secondary Gold/Amber Accent', 'haivora-logistics'),
        'section'  => 'colors',
        'settings' => 'haivora_secondary_color',
    )));
}
add_action('customize_register', 'haivora_logistics_customize_register');
