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

    <div class="section-padding" style="background-color: #F8FAFC; min-height: 85vh; display: flex; align-items: center;">
        <div class="container" style="max-width: 580px;">
            
            <div style="background: #FFFFFF; border-radius: 8px; padding: 2.5rem; box-shadow: var(--shadow-lg); border: 1px solid var(--border-color);">
                
                <div style="text-align: center; margin-bottom: 2rem;">
                    <div style="width: 48px; height: 48px; background-color: #0F172A; border-radius: 6px; display: inline-flex; align-items: center; justify-content: center; transform: rotate(45deg); margin-bottom: 1rem;">
                        <div style="width: 24px; height: 24px; border: 2px solid #FFFFFF; transform: rotate(-45deg);"></div>
                    </div>
                    <h1 style="font-size: 1.75rem; font-weight: 900; color: #0F172A; margin-bottom: 0.25rem;">
                        Create Shipping Account
                    </h1>
                    <p style="font-size: 0.85rem; color: #64748B;">
                        Register a commercial shipping account to access automated waybills, real-time cargo tracking, and delivery preferences.
                    </p>
                </div>

                <!-- Alert Banner -->
                <div id="register-alert-banner" style="display: none; padding: 0.85rem 1rem; border-radius: 6px; margin-bottom: 1.25rem; font-size: 0.85rem; font-weight: 600;"></div>

                <!-- Customer Registration Form -->
                <form id="customer-register-form">
                    
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.25rem;">
                        <div>
                            <label for="reg-firstname" style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 0.35rem;">First Name *</label>
                            <input type="text" id="reg-firstname" required placeholder="e.g. Jane" style="width: 100%; padding: 0.8rem; border: 1px solid #CBD5E1; border-radius: 6px; font-size: 0.9rem;">
                        </div>
                        <div>
                            <label for="reg-lastname" style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 0.35rem;">Last Name *</label>
                            <input type="text" id="reg-lastname" required placeholder="e.g. Smith" style="width: 100%; padding: 0.8rem; border: 1px solid #CBD5E1; border-radius: 6px; font-size: 0.9rem;">
                        </div>
                    </div>

                    <div style="margin-bottom: 1.25rem;">
                        <label for="reg-email" style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 0.35rem;">Business Email Address *</label>
                        <input type="email" id="reg-email" required placeholder="e.g. jane.smith@company.com" style="width: 100%; padding: 0.8rem; border: 1px solid #CBD5E1; border-radius: 6px; font-size: 0.9rem;">
                    </div>

                    <div style="margin-bottom: 1.25rem;">
                        <label for="reg-phone" style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 0.35rem;">Phone Number *</label>
                        <input type="tel" id="reg-phone" required placeholder="e.g. +1 (555) 019-2834" style="width: 100%; padding: 0.8rem; border: 1px solid #CBD5E1; border-radius: 6px; font-size: 0.9rem;">
                    </div>

                    <div style="margin-bottom: 1.25rem;">
                        <label for="reg-company" style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 0.35rem;">Company / Organization Name</label>
                        <input type="text" id="reg-company" placeholder="e.g. Global Tech Logistics Inc." style="width: 100%; padding: 0.8rem; border: 1px solid #CBD5E1; border-radius: 6px; font-size: 0.9rem;">
                    </div>

                    <div style="margin-bottom: 1.25rem;">
                        <label for="reg-account-type" style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 0.35rem;">Account / Shipper Type *</label>
                        <select id="reg-account-type" required style="width: 100%; padding: 0.8rem; border: 1px solid #CBD5E1; border-radius: 6px; background: #FFF; font-size: 0.9rem;">
                            <option value="corporate">Corporate Commercial Shipper</option>
                            <option value="individual">Individual / Occasional Shipper</option>
                            <option value="ecommerce">E-commerce Marketplace Seller</option>
                        </select>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.25rem;">
                        <div>
                            <label for="reg-password" style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 0.35rem;">Password *</label>
                            <input type="password" id="reg-password" required placeholder="Min 6 characters" style="width: 100%; padding: 0.8rem; border: 1px solid #CBD5E1; border-radius: 6px; font-size: 0.9rem;">
                        </div>
                        <div>
                            <label for="reg-confirm-password" style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 0.35rem;">Confirm Password *</label>
                            <input type="password" id="reg-confirm-password" required placeholder="Re-enter password" style="width: 100%; padding: 0.8rem; border: 1px solid #CBD5E1; border-radius: 6px; font-size: 0.9rem;">
                        </div>
                    </div>

                    <div style="display: flex; align-items: flex-start; gap: 0.5rem; margin-bottom: 1.5rem;">
                        <input type="checkbox" id="agree-terms" required style="margin-top: 0.2rem; width: 16px; height: 16px;">
                        <label for="agree-terms" style="font-size: 0.85rem; color: #64748B; cursor: pointer;">
                            I agree to the Terms of Service and Privacy Policy for Qidex Express Logistics operations.
                        </label>
                    </div>

                    <button type="submit" id="btn-reg-submit" class="btn btn-primary" style="width: 100%; padding: 0.9rem; font-size: 0.95rem; justify-content: center; font-weight: 800;">
                        CREATE ACCOUNT
                    </button>

                </form>

                <div style="margin-top: 1.5rem; text-align: center; border-top: 1px solid #E2E8F0; padding-top: 1.25rem;">
                    <p style="font-size: 0.85rem; color: #64748B;">
                        Already registered?
                        <a href="<?php echo esc_url(home_url('/login/')); ?>" style="color: #2563EB; font-weight: 800; text-decoration: none;">Sign In &rarr;</a>
                    </p>
                </div>

            </div>

        </div>
    </div>

</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const regForm = document.getElementById('customer-register-form');
    const alertBanner = document.getElementById('register-alert-banner');

    function showAlert(msg, isSuccess = false) {
        alertBanner.style.display = 'block';
        if (isSuccess) {
            alertBanner.style.backgroundColor = '#D1FAE5';
            alertBanner.style.color = '#047857';
            alertBanner.style.border = '1px solid #10B981';
        } else {
            alertBanner.style.backgroundColor = '#FEE2E2';
            alertBanner.style.color = '#B91C1C';
            alertBanner.style.border = '1px solid #F87171';
        }
        alertBanner.textContent = msg;
        alertBanner.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    if (regForm) {
        regForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            const firstName = document.getElementById('reg-firstname').value.trim();
            const lastName = document.getElementById('reg-lastname').value.trim();
            const email = document.getElementById('reg-email').value.trim();
            const phone = document.getElementById('reg-phone').value.trim();
            const company = document.getElementById('reg-company').value.trim();
            const accountType = document.getElementById('reg-account-type').value;
            const password = document.getElementById('reg-password').value;
            const confirmPassword = document.getElementById('reg-confirm-password').value;

            // Client-side pre-validations
            if (password !== confirmPassword) {
                showAlert('Password and Confirm Password do not match.');
                return;
            }

            if (password.length < 6) {
                showAlert('Password must be at least 6 characters in length.');
                return;
            }

            const submitBtn = document.getElementById('btn-reg-submit');
            submitBtn.disabled = true;
            submitBtn.textContent = 'CREATING ACCOUNT...';

            try {
                const response = await fetch('/api/auth/register', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        first_name: firstName,
                        last_name: lastName,
                        email: email,
                        phone: phone,
                        company: company,
                        account_type: accountType,
                        password: password,
                        confirm_password: confirmPassword
                    })
                });

                const data = await response.json();
                if (response.ok && data.success) {
                    showAlert('Account created successfully! Redirecting to your dashboard...', true);
                    setTimeout(() => {
                        window.location.href = '/dashboard/';
                    }, 1000);
                } else {
                    showAlert(data.error || 'Registration failed. Please check your details.');
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'CREATE ACCOUNT';
                }
            } catch (err) {
                showAlert('Network error occurred during registration. Please try again.');
                submitBtn.disabled = false;
                submitBtn.textContent = 'CREATE ACCOUNT';
            }
        });
    }
});
</script>

<?php
get_footer();
