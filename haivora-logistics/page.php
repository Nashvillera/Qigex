<?php
/**
 * Single Page template (Elementor Compatible)
 *
 * @package Haivora_Logistics
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<?php if (!is_front_page()) : ?>
    <div class="page-header">
        <div class="container">
            <h1 class="page-title"><?php the_title(); ?></h1>
        </div>
    </div>
<?php endif; ?>

<main id="primary" class="site-main container section-padding">
    <?php
    while (have_posts()) :
        the_post();
        ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <div class="entry-content">
                <?php
                the_content();

                wp_link_pages(array(
                    'before' => '<div class="page-links">' . esc_html__('Pages:', 'haivora-logistics'),
                    'after'  => '</div>',
                ));
                ?>
            </div>
        </article>

        <?php
        if (comments_open() || get_comments_number()) :
            comments_template();
        endif;

    endwhile;
    ?>
</main>

<?php
get_footer();
