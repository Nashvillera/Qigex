<?php
/**
 * Single post template
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
        <div style="font-size: 0.85rem; color: var(--secondary); font-weight: 700; text-transform: uppercase; margin-bottom: 0.5rem;">
            <?php the_category(', '); ?>
        </div>
        <h1 class="page-title"><?php the_title(); ?></h1>
        <div style="font-size: 0.9rem; color: var(--text-dim); margin-top: 0.5rem;">
            <?php haivora_logistics_posted_on(); ?> | <?php haivora_logistics_posted_by(); ?>
        </div>
    </div>
</div>

<main id="primary" class="site-main container section-padding" style="max-width: 900px;">
    <?php
    while (have_posts()) :
        the_post();
        ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <?php if (has_post_thumbnail()) : ?>
                <div style="margin-bottom: 2rem; border-radius: 12px; overflow: hidden; box-shadow: var(--shadow-md);">
                    <?php the_post_thumbnail('full'); ?>
                </div>
            <?php endif; ?>

            <div class="entry-content">
                <?php the_content(); ?>
            </div>
        </article>

        <?php
        the_post_navigation(array(
            'prev_text' => '<span class="nav-subtitle">' . esc_html__('Previous Article:', 'haivora-logistics') . '</span> <span class="nav-title">%title</span>',
            'next_text' => '<span class="nav-subtitle">' . esc_html__('Next Article:', 'haivora-logistics') . '</span> <span class="nav-title">%title</span>',
        ));

        if (comments_open() || get_comments_number()) :
            comments_template();
        endif;

    endwhile;
    ?>
</main>

<?php
get_footer();
