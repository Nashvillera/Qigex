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

    <div class="section-padding" style="background-color: #F8FAFC; min-height: 80vh; display: flex; align-items: center;">
        <div class="container" style="max-width: 480px;">
            
            <div style="background: #FFFFFF; border-radius: 8px; padding: 2.5rem; box-shadow: var(--shadow-lg); border: 1px solid var(--border-color);">
                
                <div style="text-align: center; margin-bottom: 2rem;">
                    <div style="width: 48px; height: 48px; background-color: #0F172A; border-radius: 6px; display: inline-flex; align-items: center; justify-content: center; transform: rotate(45deg); margin-bottom: 1rem;">
                        <div style="width: 24px; height: 24px; border: 2px solid #FFFFFF; transform: rotate(-45deg);"></div>
                    </div>
                    <h1 style="font-size: 1.75rem; font-weight: 900; color: #0F172A; margin-bottom: 0.25rem;">
                        Customer Portal Login
                    </h1>
                    <p style="font-size: 0.85rem; color: #64748B;">
                        Sign in to access your shipping dashboard, live waybills, and real-time telemetry.
                    </p>
                </div>

                <!-- Alert Banner -->
                <div id="auth-alert-banner" style="display: none; padding: 0.85rem 1rem; border-radius: 6px; margin-bottom: 1.25rem; font-size: 0.85rem; font-weight: 600;"></div>

                <!-- Customer Login Form -->
                <form id="customer-login-form">
                    
                    <div style="margin-bottom: 1.25rem;">
                        <label for="login-email" style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 0.35rem;">Email Address or Username *</label>
                        <input type="text" id="login-email" required placeholder="e.g. client@company.com" style="width: 100%; padding: 0.8rem; border: 1px solid #CBD5E1; border-radius: 6px; font-size: 0.9rem;">
                    </div>

                    <div style="margin-bottom: 1.25rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem;">
                            <label for="login-password" style="font-size: 0.85rem; font-weight: 700; color: #0F172A;">Password *</label>
                            <a href="#forgot-password" id="btn-show-forgot" style="font-size: 0.8rem; color: #2563EB; font-weight: 700; text-decoration: none;">Forgot Password?</a>
                        </div>
                        <input type="password" id="login-password" required placeholder="••••••••••••" style="width: 100%; padding: 0.8rem; border: 1px solid #CBD5E1; border-radius: 6px; font-size: 0.9rem;">
                    </div>

                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.5rem;">
                        <input type="checkbox" id="rememberme" style="width: 16px; height: 16px;">
                        <label for="rememberme" style="font-size: 0.85rem; color: #64748B; cursor: pointer;">Keep me signed in on this device</label>
                    </div>

                    <button type="submit" id="btn-login-submit" class="btn btn-primary" style="width: 100%; padding: 0.9rem; font-size: 0.95rem; justify-content: center; font-weight: 800;">
                        SIGN IN TO PORTAL
                    </button>

                </form>

                <!-- Forgot Password Box (Toggleable) -->
                <div id="forgot-password-box" style="display: none; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px dashed #CBD5E1;">
                    <h3 style="font-size: 1rem; font-weight: 800; color: #0F172A; margin-bottom: 0.5rem;">Reset Account Password</h3>
                    <p style="font-size: 0.8rem; color: #64748B; margin-bottom: 1rem;">Enter your registered account email to receive a password reset code or instructions.</p>
                    
                    <div style="margin-bottom: 1rem;">
                        <input type="email" id="forgot-email" placeholder="Your registered email address" style="width: 100%; padding: 0.75rem; border: 1px solid #CBD5E1; border-radius: 6px; font-size: 0.85rem;">
                    </div>
                    
                    <div style="display: flex; gap: 0.5rem;">
                        <button type="button" id="btn-submit-forgot" class="btn btn-primary" style="flex: 1; padding: 0.65rem; font-size: 0.85rem;">Send Reset Link</button>
                        <button type="button" id="btn-cancel-forgot" style="background: #E2E8F0; border: none; border-radius: 6px; padding: 0.65rem 1rem; font-size: 0.85rem; font-weight: 700; color: #334155; cursor: pointer;">Cancel</button>
                    </div>
                </div>

                <div style="margin-top: 1.5rem; text-align: center; border-top: 1px solid #E2E8F0; padding-top: 1.25rem;">
                    <p style="font-size: 0.85rem; color: #64748B;">
                        Don't have a Qidex shipping account?
                        <a href="<?php echo esc_url(home_url('/register/')); ?>" style="color: #2563EB; font-weight: 800; text-decoration: none;">Register Account &rarr;</a>
                    </p>
                </div>

                <!-- Demo Account Presets -->
                <div style="margin-top: 1.25rem; background-color: #F8FAFC; border: 1px solid #E2E8F0; padding: 0.85rem; border-radius: 6px; font-size: 0.8rem; color: #475569;">
                    <strong style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: #0F172A; margin-bottom: 0.35rem;">💡 Demo Customer Accounts:</strong>
                    <div style="display: flex; flex-wrap: wrap; gap: 0.5rem; margin-top: 0.5rem;">
                        <button type="button" class="demo-login-btn" data-email="client@acmetech.com" data-pass="Acme2026!" style="background: #EFF6FF; color: #1D4ED8; border: 1px solid #BFDBFE; padding: 4px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 700; cursor: pointer;">
                            Acme Tech Shipper
                        </button>
                        <button type="button" class="demo-login-btn" data-email="hamburg.machinery@global.de" data-pass="Hamburg2026!" style="background: #EFF6FF; color: #1D4ED8; border: 1px solid #BFDBFE; padding: 4px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 700; cursor: pointer;">
                            Hamburg Industrial
                        </button>
                    </div>
                </div>

            </div>

        </div>
    </div>

</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('customer-login-form');
    const alertBanner = document.getElementById('auth-alert-banner');
    const btnForgot = document.getElementById('btn-show-forgot');
    const forgotBox = document.getElementById('forgot-password-box');
    const btnCancelForgot = document.getElementById('btn-cancel-forgot');
    const btnSubmitForgot = document.getElementById('btn-submit-forgot');

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
    }

    if (loginForm) {
        loginForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const email = document.getElementById('login-email').value.trim();
            const password = document.getElementById('login-password').value;
            const rememberme = document.getElementById('rememberme').checked;

            const submitBtn = document.getElementById('btn-login-submit');
            submitBtn.disabled = true;
            submitBtn.textContent = 'AUTHENTICATING...';

            try {
                const response = await fetch('/api/auth/login', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ log: email, pwd: password, rememberme: rememberme })
                });

                const data = await response.json();
                if (response.ok && data.success) {
                    showAlert('Authentication successful! Redirecting to customer dashboard...', true);
                    setTimeout(() => {
                        window.location.href = '/dashboard/';
                    }, 800);
                } else {
                    showAlert(data.error || 'Login failed. Please check your credentials.');
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'SIGN IN TO PORTAL';
                }
            } catch (err) {
                showAlert('Network error occurred during login. Please try again.');
                submitBtn.disabled = false;
                submitBtn.textContent = 'SIGN IN TO PORTAL';
            }
        });
    }

    // Toggle Forgot Password Box
    if (btnForgot && forgotBox) {
        btnForgot.addEventListener('click', function(e) {
            e.preventDefault();
            forgotBox.style.display = 'block';
            forgotBox.scrollIntoView({ behavior: 'smooth' });
        });
    }

    if (btnCancelForgot && forgotBox) {
        btnCancelForgot.addEventListener('click', function() {
            forgotBox.style.display = 'none';
        });
    }

    if (btnSubmitForgot) {
        btnSubmitForgot.addEventListener('click', async function() {
            const email = document.getElementById('forgot-email').value.trim();
            if (!email) {
                showAlert('Please enter your email address to reset password.');
                return;
            }

            try {
                const res = await fetch('/api/auth/forgot-password', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ email: email })
                });
                const data = await res.json();
                if (res.ok) {
                    showAlert(data.message || 'Password reset instructions have been dispatched to your email.', true);
                    forgotBox.style.display = 'none';
                } else {
                    showAlert(data.error || 'Unable to process password reset request.');
                }
            } catch(e) {
                showAlert('Request failed: ' + e.message);
            }
        });
    }

    // Demo Account Fill
    document.querySelectorAll('.demo-login-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('login-email').value = this.getAttribute('data-email');
            document.getElementById('login-password').value = this.getAttribute('data-pass');
            showAlert('Demo credentials auto-filled. Click SIGN IN TO PORTAL to proceed.', true);
        });
    });
});
</script>

<?php
get_footer();
