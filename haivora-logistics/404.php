<?php
/**
 * 404 Error page template
 *
 * @package Haivora_Logistics
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main id="primary" class="site-main container section-padding" style="text-align: center; max-width: 650px;">
    <div style="font-size: 6rem; font-weight: 900; color: var(--secondary); line-height: 1; margin-bottom: 1rem;">404</div>
    <h1 class="section-title" style="margin-bottom: 1rem;"><?php esc_html_e('Route Not Found', 'haivora-logistics'); ?></h1>
    <p style="color: var(--text-muted); font-size: 1.1rem; margin-bottom: 2rem;">
        <?php esc_html_e('The requested logistics page or shipment record appears to have been relocated or does not exist.', 'haivora-logistics'); ?>
    </p>

    <div style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary">
            &larr; <?php esc_html_e('Return to Home', 'haivora-logistics'); ?>
        </a>
        <a href="<?php echo esc_url(home_url('/#track')); ?>" class="btn btn-dark">
            <?php esc_html_e('Track a Shipment', 'haivora-logistics'); ?>
        </a>
    </div>
</main>

<?php
get_footer();
