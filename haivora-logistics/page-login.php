<?php
/**
 * Template Name: Login Page
 * Page Template for /login/
 *
 * @package Haivora_Logistics
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main id="primary" class="site-main">

    <div class="section-padding" style="background-color: #F8FAFC; min-height: 75vh; display: flex; align-items: center;">
        <div class="container" style="max-width: 480px;">
            
            <div style="background: #FFFFFF; border-radius: 6px; padding: 2.5rem; box-shadow: var(--shadow-lg); border: 1px solid var(--border-color);">
                
                <div style="text-align: center; margin-bottom: 2rem;">
                    <div style="width: 48px; height: 48px; background-color: #0F172A; border-radius: 4px; display: inline-flex; align-items: center; justify-content: center; transform: rotate(45deg); margin-bottom: 1rem;">
                        <div style="width: 24px; height: 24px; border: 2px solid #FFFFFF; transform: rotate(-45deg);"></div>
                    </div>
                    <h1 style="font-size: 1.75rem; font-weight: 900; color: #0F172A; margin-bottom: 0.25rem;">
                        Customer Portal Login
                    </h1>
                    <p style="font-size: 0.85rem; color: #64748B;">
                        Sign in to access your shipping dashboard, waybills, and invoice records.
                    </p>
                </div>

                <!-- Customer Login Form UI -->
                <form id="customer-login-form" action="" method="post" onsubmit="event.preventDefault(); alert('Customer portal authentication system will be connected in Phase 5.'); return false;">
                    
                    <div style="margin-bottom: 1.25rem;">
                        <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 0.35rem;">Email Address or Account ID *</label>
                        <input type="text" required placeholder="e.g. client@company.com" style="width: 100%; padding: 0.8rem; border: 1px solid #CBD5E1; border-radius: 4px;">
                    </div>

                    <div style="margin-bottom: 1.25rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem;">
                            <label style="font-size: 0.85rem; font-weight: 700; color: #0F172A;">Password *</label>
                            <a href="#" onclick="alert('Password recovery will be activated in Phase 5.'); return false;" style="font-size: 0.8rem; color: #2563EB; font-weight: 600;">Forgot Password?</a>
                        </div>
                        <input type="password" required placeholder="••••••••••••" style="width: 100%; padding: 0.8rem; border: 1px solid #CBD5E1; border-radius: 4px;">
                    </div>

                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.5rem;">
                        <input type="checkbox" id="remember-me">
                        <label for="remember-me" style="font-size: 0.85rem; color: #64748B;">Keep me signed in on this device</label>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.9rem; font-size: 0.95rem;">
                        SIGN IN TO PORTAL
                    </button>

                </form>

                <div style="margin-top: 1.5rem; text-align: center; border-top: 1px solid #E2E8F0; padding-top: 1.25rem;">
                    <p style="font-size: 0.85rem; color: #64748B;">
                        Don't have a Qidex shipping account?
                        <a href="<?php echo esc_url(home_url('/register/')); ?>" style="color: #2563EB; font-weight: 700;">Register Account &rarr;</a>
                    </p>
                </div>

                <div style="margin-top: 1rem; background-color: #F1F5F9; padding: 0.75rem; border-radius: 4px; font-size: 0.75rem; color: #64748B; text-align: center;">
                    🔒 Phase 5 Customer Authentication Module Placeholder
                </div>

            </div>

        </div>
    </div>

</main>

<?php
get_footer();
