<?php
/**
 * Header template for Haivora Logistics theme
 *
 * @package Haivora_Logistics
 */

if (!defined('ABSPATH')) {
    exit;
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@500;700;800&family=Outfit:wght@500;600;700;800;900&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'haivora-logistics'); ?></a>

<!-- Announcement Top Bar -->
<div class="top-bar">
    <div class="container">
        <div class="top-bar-contact">
            <div class="top-bar-item">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                <span>24/7 Hotline: <?php echo esc_html(haivora_logistics_phone()); ?></span>
            </div>
            <div class="top-bar-item">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                <span><?php echo esc_html(haivora_logistics_email()); ?></span>
            </div>
            <div class="top-bar-item">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                <span>Global Hubs: NY | London | Singapore | Dubai</span>
            </div>
        </div>
        <div class="top-bar-meta">
            <a href="/shipment-admin" class="top-bar-badge" style="background: rgba(245, 158, 11, 0.2); color: #F59E0B; text-decoration: none; font-weight: 800; border: 1px solid rgba(245, 158, 11, 0.4);">
                📦 Shipment Admin Portal
            </a>
            <span class="top-bar-badge">ISO 9001 Certified</span>
            <span class="top-bar-badge" style="background: rgba(0,168,232,0.15); color: #00A8E8;">Global Coverage</span>
        </div>
    </div>
</div>

<!-- Main Site Header -->
<header id="masthead" class="site-header">
    <div class="container">
        <div class="site-header-inner">
            
            <!-- Site Branding -->
            <?php haivora_logistics_site_logo(); ?>

            <!-- Navigation Bar -->
            <nav id="site-navigation" class="main-navigation" aria-label="<?php esc_attr_e('Primary Navigation', 'haivora-logistics'); ?>">
                <?php
                if (has_nav_menu('primary')) {
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'menu_id'        => 'primary-menu',
                        'menu_class'     => 'nav-menu',
                        'container'      => false,
                    ));
                } else {
                    // Fallback Navigation Menu
                    ?>
                    <ul id="primary-menu" class="nav-menu">
                        <li class="menu-item current-menu-item"><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'haivora-logistics'); ?></a></li>
                        <li class="menu-item"><a href="<?php echo esc_url(home_url('/#track')); ?>"><?php esc_html_e('Track Shipment', 'haivora-logistics'); ?></a></li>
                        <li class="menu-item"><a href="<?php echo esc_url(home_url('/#services')); ?>"><?php esc_html_e('Services', 'haivora-logistics'); ?></a></li>
                        <li class="menu-item"><a href="<?php echo esc_url(home_url('/#why-us')); ?>"><?php esc_html_e('About Us', 'haivora-logistics'); ?></a></li>
                        <li class="menu-item"><a href="<?php echo esc_url(home_url('/#quote')); ?>"><?php esc_html_e('Get a Quote', 'haivora-logistics'); ?></a></li>
                        <li class="menu-item"><a href="<?php echo esc_url(home_url('/#contact')); ?>"><?php esc_html_e('Contact', 'haivora-logistics'); ?></a></li>
                    </ul>
                    <?php
                }
                ?>
            </nav>

            <!-- Actions & Mobile Menu Button -->
            <div class="header-actions">
                <a href="#track" class="btn btn-primary">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <?php esc_html_e('TRACK SHIPMENT', 'haivora-logistics'); ?>
                </a>

                <button class="mobile-toggle" aria-controls="primary-menu" aria-expanded="false" aria-label="<?php esc_attr_e('Toggle navigation', 'haivora-logistics'); ?>">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                </button>
            </div>

        </div>
    </div>
</header>
