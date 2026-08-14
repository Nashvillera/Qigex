<?php
/**
 * Main template file for Haivora Logistics theme
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
        <h1 class="page-title"><?php single_post_title(); ?></h1>
    </div>
</div>

<main id="primary" class="site-main container section-padding">
    <div style="display: grid; grid-template-columns: 1fr; gap: 2rem;">
        <?php
        if (have_posts()) :
            while (have_posts()) :
                the_post();
                ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('service-card'); ?>>
                    <h2 style="font-size: 1.5rem; color: var(--primary); margin-bottom: 0.5rem;">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h2>
                    <div style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1rem;">
                        <?php haivora_logistics_posted_on(); ?> | <?php haivora_logistics_posted_by(); ?>
                    </div>
                    <div class="entry-content">
                        <?php the_excerpt(); ?>
                    </div>
                    <a href="<?php the_permalink(); ?>" class="btn btn-primary" style="margin-top: 1rem; align-self: flex-start;">Read Article &rarr;</a>
                </article>
                <?php
            endwhile;

            the_posts_navigation();
        else :
            ?>
            <p><?php esc_html_e('No posts found matching your criteria.', 'haivora-logistics'); ?></p>
            <?php
        endif;
        ?>
    </div>
</main>

<?php
get_footer();
