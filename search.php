<?php
/**
 * Search results template
 *
 * @package Haivora_Logistics
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<div class="page-header">
    <div class="container">
        <h1 class="page-title">
            <?php
            /* translators: %s: search query. */
            printf(esc_html__('Search Results for: %s', 'haivora-logistics'), '<span>' . get_search_query() . '</span>');
            ?>
        </h1>
    </div>
</div>

<main id="primary" class="site-main container section-padding">
    <?php if (have_posts()) : ?>
        <div class="services-grid">
            <?php
            while (have_posts()) :
                the_post();
                ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('service-card'); ?>>
                    <h2 style="font-size: 1.25rem; font-weight: 800; color: var(--primary); margin-bottom: 0.75rem;">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h2>
                    <div style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1.25rem;">
                        <?php the_excerpt(); ?>
                    </div>
                    <a href="<?php the_permalink(); ?>" class="service-link">View Page &rarr;</a>
                </article>
                <?php
            endwhile;

            the_posts_navigation();
            ?>
        </div>
    <?php else : ?>
        <div style="max-width: 600px; margin: 0 auto; text-align: center;">
            <p style="margin-bottom: 1.5rem; color: var(--text-muted);"><?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with different keywords.', 'haivora-logistics'); ?></p>
            <?php get_search_form(); ?>
        </div>
    <?php endif; ?>
</main>

<?php
get_footer();
