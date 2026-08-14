<?php
/**
 * Custom template tags for Haivora Logistics theme
 *
 * @package Haivora_Logistics
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('haivora_logistics_posted_on')) :
    /**
     * Prints HTML with meta information for the current post-date/time.
     */
    function haivora_logistics_posted_on() {
        $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
        if (get_the_time('U') !== get_the_modified_time('U')) {
            $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated screen-reader-text" datetime="%3$s">%4$s</time>';
        }

        $time_string = sprintf(
            $time_string,
            esc_attr(get_the_date(DATE_W3C)),
            esc_html(get_the_date()),
            esc_attr(get_the_modified_date(DATE_W3C)),
            esc_html(get_the_modified_date())
        );

        $posted_on = sprintf(
            /* translators: %s: post date. */
            esc_html_x('Posted on %s', 'post date', 'haivora-logistics'),
            '<a href="' . esc_url(get_permalink()) . '" rel="bookmark">' . $time_string . '</a>'
        );

        echo '<span class="posted-on">' . $posted_on . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
endif;

if (!function_exists('haivora_logistics_posted_by')) :
    /**
     * Prints HTML with meta information for the current author.
     */
    function haivora_logistics_posted_by() {
        $byline = sprintf(
            /* translators: %s: post author. */
            esc_html_x('by %s', 'post author', 'haivora-logistics'),
            '<span class="author vcard"><a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a></span>'
        );

        echo '<span class="byline"> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
endif;

if (!function_exists('haivora_logistics_site_logo')) :
    /**
     * Renders Custom Logo or default Qidex Express LOGISTICS brand symbol.
     */
    function haivora_logistics_site_logo() {
        if (has_custom_logo()) {
            the_custom_logo();
        } else {
            $company_name = haivora_logistics_company_name();
            ?>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="site-branding" rel="home">
                <div class="brand-logo-symbol">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
                        <polyline points="2 17 12 22 22 17"></polyline>
                        <polyline points="2 12 12 17 22 12"></polyline>
                    </svg>
                </div>
                <span class="brand-title">
                    <?php echo esc_html($company_name); ?>
                    <span class="sub-text">GLOBAL EXPRESS</span>
                </span>
            </a>
            <?php
        }
    }
endif;
