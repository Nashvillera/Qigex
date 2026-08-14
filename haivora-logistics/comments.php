<?php
/**
 * Comments template
 *
 * @package Haivora_Logistics
 */

if (!defined('ABSPATH')) {
    exit;
}

if (post_password_required()) {
    return;
}
?>

<div id="comments" class="comments-area" style="margin-top: 3rem; padding-top: 2rem; border-top: 1px solid var(--border-color);">

    <?php if (have_comments()) : ?>
        <h2 class="comments-title" style="font-size: 1.5rem; color: var(--primary); margin-bottom: 1.5rem;">
            <?php
            $comment_count = get_comments_number();
            if ('1' === $comment_count) {
                printf(esc_html__('One comment on &ldquo;%1$s&rdquo;', 'haivora-logistics'), '<span>' . wp_kses_post(get_the_title()) . '</span>');
            } else {
                printf(
                    /* translators: 1: comment count, 2: title. */
                    esc_html(_nx('%1$s comment on &ldquo;%2$s&rdquo;', '%1$s comments on &ldquo;%2$s&rdquo;', $comment_count, 'comments title', 'haivora-logistics')),
                    number_format_i18n($comment_count), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    '<span>' . wp_kses_post(get_the_title()) . '</span>'
                );
            }
            ?>
        </h2>

        <ol class="comment-list" style="list-style: none; margin-bottom: 2rem;">
            <?php
            wp_list_comments(array(
                'style'      => 'ol',
                'short_ping' => true,
            ));
            ?>
        </ol>

        <?php
        the_comments_navigation();

        if (!comments_open()) :
            ?>
            <p class="no-comments"><?php esc_html_e('Comments are closed.', 'haivora-logistics'); ?></p>
            <?php
        endif;

    endif;

    comment_form();
    ?>

</div>
