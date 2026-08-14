<?php
/**
 * Search form template
 *
 * @package Haivora_Logistics
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>" style="display: flex; gap: 0.5rem;">
    <label for="search-input-field" class="screen-reader-text"><?php esc_html_e('Search for:', 'haivora-logistics'); ?></label>
    <input type="search" id="search-input-field" class="tracking-input" placeholder="<?php echo esc_attr_x('Search logistics info...', 'placeholder', 'haivora-logistics'); ?>" value="<?php echo get_search_query(); ?>" name="s" required style="padding: 0.75rem 1rem;" />
    <button type="submit" class="btn btn-primary" style="padding: 0.75rem 1.25rem;">
        <?php echo esc_html_x('Search', 'submit button', 'haivora-logistics'); ?>
    </button>
</form>
