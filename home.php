<?php
/**
 * Blog Posts page template
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
        <h1 class="page-title"><?php esc_html_e('Logistics Insights & Industry News', 'haivora-logistics'); ?></h1>
        <p style="color: var(--text-dim); margin-top: 0.5rem; font-size: 1.1rem;">Latest updates on ocean trade lanes, air freight rates, customs compliance, and supply chain technology.</p>
    </div>
</div>

<main id="primary" class="site-main container section-padding">
    <div class="services-grid">
        <?php
        if (have_posts()) :
            while (have_posts()) :
                the_post();
                ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('service-card'); ?>>
                    <?php if (has_post_thumbnail()) : ?>
                        <div style="margin-bottom: 1rem; border-radius: 8px; overflow: hidden;">
                            <?php the_post_thumbnail('medium_large'); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div style="font-size: 0.8rem; color: var(--secondary); font-weight: 700; text-transform: uppercase; margin-bottom: 0.4rem;">
                        <?php the_category(', '); ?>
                    </div>

                    <h2 style="font-size: 1.25rem; font-weight: 800; color: var(--primary); margin-bottom: 0.75rem;">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h2>

                    <div style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1.25rem;">
                        <?php the_excerpt(); ?>
                    </div>

                    <a href="<?php the_permalink(); ?>" class="service-link">
                        Read Insights &rarr;
                    </a>
                </article>
                <?php
            endwhile;

            the_posts_navigation();
        else :
            ?>
            <p><?php esc_html_e('No industry insights published yet.', 'haivora-logistics'); ?></p>
            <?php
        endif;
        ?>
    </div>
</main>

<?php
get_footer();
