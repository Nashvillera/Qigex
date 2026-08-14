<?php
/**
 * Footer template for Haivora Logistics theme
 *
 * @package Haivora_Logistics
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<!-- Site Footer -->
<footer id="colophon" class="site-footer">
    <div class="container">
        <div class="footer-grid">
            
            <!-- Column 1: Company Profile -->
            <div class="footer-widget">
                <div class="footer-branding" style="margin-bottom: 1.25rem;">
                    <?php haivora_logistics_site_logo(); ?>
                </div>
                <p style="margin-bottom: 1.25rem; font-size: 0.925rem; line-height: 1.6;">
                    <?php echo esc_html(haivora_logistics_company_name()); ?> is a premier global freight forwarding and supply chain solutions provider, delivering seamless logistics and real-time cargo visibility across 220+ countries.
                </p>
                <div style="display: flex; gap: 0.5rem;">
                    <span class="top-bar-badge">Security Assured</span>
                    <span class="top-bar-badge" style="background: rgba(16, 185, 129, 0.15); color: #10B981;">AEO Certified</span>
                </div>
            </div>

            <!-- Column 2: Quick Links / Navigation -->
            <div class="footer-widget">
                <h3 class="footer-widget-title"><?php esc_html_e('Quick Links', 'haivora-logistics'); ?></h3>
                <?php
                if (has_nav_menu('footer')) {
                    wp_nav_menu(array(
                        'theme_location' => 'footer',
                        'menu_class'     => 'footer-links',
                        'container'      => false,
                    ));
                } else {
                    ?>
                    <ul class="footer-links">
                        <li><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'haivora-logistics'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/#track')); ?>"><?php esc_html_e('Track Shipment', 'haivora-logistics'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/#services')); ?>"><?php esc_html_e('Our Services', 'haivora-logistics'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/#why-us')); ?>"><?php esc_html_e('Why Choose Us', 'haivora-logistics'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/#quote')); ?>"><?php esc_html_e('Request a Quote', 'haivora-logistics'); ?></a></li>
                    </ul>
                    <?php
                }
                ?>
            </div>

            <!-- Column 3: Logistics Services -->
            <div class="footer-widget">
                <h3 class="footer-widget-title"><?php esc_html_e('Logistics Services', 'haivora-logistics'); ?></h3>
                <ul class="footer-links">
                    <li><a href="<?php echo esc_url(home_url('/#services')); ?>"><?php esc_html_e('Air Freight Express', 'haivora-logistics'); ?></a></li>
                    <li><a href="<?php echo esc_url(home_url('/#services')); ?>"><?php esc_html_e('Ocean & Sea Cargo', 'haivora-logistics'); ?></a></li>
                    <li><a href="<?php echo esc_url(home_url('/#services')); ?>"><?php esc_html_e('Overland Road Transport', 'haivora-logistics'); ?></a></li>
                    <li><a href="<?php echo esc_url(home_url('/#services')); ?>"><?php esc_html_e('Warehousing & Distribution', 'haivora-logistics'); ?></a></li>
                    <li><a href="<?php echo esc_url(home_url('/#services')); ?>"><?php esc_html_e('Customs Clearance', 'haivora-logistics'); ?></a></li>
                </ul>
            </div>

            <!-- Column 4: Contact & Newsletter -->
            <div class="footer-widget">
                <h3 class="footer-widget-title"><?php esc_html_e('Global Headquarters', 'haivora-logistics'); ?></h3>
                <p style="font-size: 0.9rem; margin-bottom: 0.75rem;">
                    <strong>Address:</strong> <?php echo esc_html(haivora_logistics_address()); ?>
                </p>
                <p style="font-size: 0.9rem; margin-bottom: 0.75rem;">
                    <strong>Phone:</strong> <?php echo esc_html(haivora_logistics_phone()); ?>
                </p>
                <p style="font-size: 0.9rem; margin-bottom: 1.25rem;">
                    <strong>Email:</strong> <?php echo esc_html(haivora_logistics_email()); ?>
                </p>

                <!-- Newsletter Form Placeholder -->
                <form class="footer-newsletter-form" onsubmit="event.preventDefault(); alert('Newsletter subscription received!');">
                    <label for="footer-email-input" class="screen-reader-text"><?php esc_html_e('Subscribe to Shipment Updates', 'haivora-logistics'); ?></label>
                    <input type="email" id="footer-email-input" class="footer-input" placeholder="Enter corporate email..." required>
                    <button type="submit" class="btn btn-primary" style="padding: 0.6rem 1rem; font-size: 0.85rem;">
                        <?php esc_html_e('Subscribe for Updates', 'haivora-logistics'); ?>
                    </button>
                </form>
            </div>

        </div>
    </div>

    <!-- Footer Bottom Bar -->
    <div class="footer-bottom">
        <div class="container">
            <div>
                &copy; <?php echo esc_html(date('Y')); ?> <strong><?php echo esc_html(haivora_logistics_company_name()); ?></strong>. <?php esc_html_e('All Rights Reserved.', 'haivora-logistics'); ?>
            </div>
            <div style="display: flex; gap: 1.5rem; font-size: 0.8rem;">
                <a href="#"><?php esc_html_e('Privacy Policy', 'haivora-logistics'); ?></a>
                <a href="#"><?php esc_html_e('Terms of Service', 'haivora-logistics'); ?></a>
                <a href="#"><?php esc_html_e('Security & Compliance', 'haivora-logistics'); ?></a>
            </div>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
