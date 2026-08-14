<?php
/**
 * Template Name: Register Page
 * Page Template for /register/
 *
 * @package Haivora_Logistics
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main id="primary" class="site-main">

    <div class="section-padding" style="background-color: #F8FAFC; min-height: 80vh; display: flex; align-items: center;">
        <div class="container" style="max-width: 560px;">
            
            <div style="background: #FFFFFF; border-radius: 6px; padding: 2.5rem; box-shadow: var(--shadow-lg); border: 1px solid var(--border-color);">
                
                <div style="text-align: center; margin-bottom: 2rem;">
                    <div style="width: 48px; height: 48px; background-color: #0F172A; border-radius: 4px; display: inline-flex; align-items: center; justify-content: center; transform: rotate(45deg); margin-bottom: 1rem;">
                        <div style="width: 24px; height: 24px; border: 2px solid #FFFFFF; transform: rotate(-45deg);"></div>
                    </div>
                    <h1 style="font-size: 1.75rem; font-weight: 900; color: #0F172A; margin-bottom: 0.25rem;">
                        Create Shipping Account
                    </h1>
                    <p style="font-size: 0.85rem; color: #64748B;">
                        Register for a commercial account to manage waybills and automated cargo dispatch.
                    </p>
                </div>

                <!-- Registration Form UI -->
                <form id="customer-register-form" action="" method="post" onsubmit="event.preventDefault(); alert('Customer account creation system will be connected in Phase 5.'); return false;">
                    
                    <div style="margin-bottom: 1.25rem;">
                        <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 0.35rem;">Full Name *</label>
                        <input type="text" required placeholder="e.g. Jane Smith" style="width: 100%; padding: 0.8rem; border: 1px solid #CBD5E1; border-radius: 4px;">
                    </div>

                    <div style="margin-bottom: 1.25rem;">
                        <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 0.35rem;">Company / Organization Name</label>
                        <input type="text" placeholder="e.g. Global Trade Ltd" style="width: 100%; padding: 0.8rem; border: 1px solid #CBD5E1; border-radius: 4px;">
                    </div>

                    <div style="margin-bottom: 1.25rem;">
                        <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 0.35rem;">Business Email Address *</label>
                        <input type="email" required placeholder="e.g. jane@company.com" style="width: 100%; padding: 0.8rem; border: 1px solid #CBD5E1; border-radius: 4px;">
                    </div>

                    <div style="margin-bottom: 1.25rem;">
                        <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 0.35rem;">Phone Number *</label>
                        <input type="tel" required placeholder="e.g. +1 (555) 019-2834" style="width: 100%; padding: 0.8rem; border: 1px solid #CBD5E1; border-radius: 4px;">
                    </div>

                    <div style="margin-bottom: 1.25rem;">
                        <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 0.35rem;">Account Type *</label>
                        <select required style="width: 100%; padding: 0.8rem; border: 1px solid #CBD5E1; border-radius: 4px; background: #FFF;">
                            <option value="corporate">Corporate Commercial Shipper</option>
                            <option value="individual">Individual / Occasional Shipper</option>
                            <option value="ecommerce">E-commerce Marketplace Seller</option>
                        </select>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.25rem;">
                        <div>
                            <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 0.35rem;">Password *</label>
                            <input type="password" required placeholder="••••••••••••" style="width: 100%; padding: 0.8rem; border: 1px solid #CBD5E1; border-radius: 4px;">
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 0.35rem;">Confirm Password *</label>
                            <input type="password" required placeholder="••••••••••••" style="width: 100%; padding: 0.8rem; border: 1px solid #CBD5E1; border-radius: 4px;">
                        </div>
                    </div>

                    <div style="display: flex; align-items: flex-start; gap: 0.5rem; margin-bottom: 1.5rem;">
                        <input type="checkbox" id="agree-terms" required style="margin-top: 0.2rem;">
                        <label for="agree-terms" style="font-size: 0.85rem; color: #64748B;">
                            I agree to the Terms of Service and Privacy Policy for Qidex Global Freight operations.
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.9rem; font-size: 0.95rem;">
                        CREATE ACCOUNT
                    </button>

                </form>

                <div style="margin-top: 1.5rem; text-align: center; border-top: 1px solid #E2E8F0; padding-top: 1.25rem;">
                    <p style="font-size: 0.85rem; color: #64748B;">
                        Already registered?
                        <a href="<?php echo esc_url(home_url('/login/')); ?>" style="color: #2563EB; font-weight: 700;">Sign In &rarr;</a>
                    </p>
                </div>

                <div style="margin-top: 1rem; background-color: #F1F5F9; padding: 0.75rem; border-radius: 4px; font-size: 0.75rem; color: #64748B; text-align: center;">
                    🔒 Phase 5 Customer Registration System Placeholder
                </div>

            </div>

        </div>
    </div>

</main>

<?php
get_footer();
