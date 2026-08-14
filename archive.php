<?php
/**
 * Archive template
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
        <?php the_archive_title('<h1 class="page-title">', '</h1>'); ?>
        <?php the_archive_description('<div class="archive-description" style="color: var(--text-dim); margin-top: 0.5rem;">', '</div>'); ?>
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
                    <h2 style="font-size: 1.25rem; font-weight: 800; color: var(--primary); margin-bottom: 0.75rem;">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h2>
                    <div style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1.25rem;">
                        <?php the_excerpt(); ?>
                    </div>
                    <a href="<?php the_permalink(); ?>" class="service-link">Read More &rarr;</a>
                </article>
                <?php
            endwhile;

            the_posts_navigation();
        else :
            ?>
            <p><?php esc_html_e('No archives found.', 'haivora-logistics'); ?></p>
            <?php
        endif;
        ?>
    </div>
</main>

<?php
get_footer();
