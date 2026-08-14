<?php
/**
 * Template Name: Contact Page
 * Page Template for /contact/
 *
 * @package Haivora_Logistics
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();

$wa_link = function_exists('haivora_get_whatsapp_link') ? haivora_get_whatsapp_link() : 'https://wa.me/18005557433';
?>

<main id="primary" class="site-main">

    <!-- Page Header Banner -->
    <div style="background-color: #0F172A; color: #FFFFFF; padding: 3.5rem 0 3rem; border-bottom: 1px solid #1E293B;">
        <div class="container">
            <div style="display: inline-block; padding: 0.25rem 0.75rem; background: rgba(37, 99, 235, 0.2); color: #38BDF8; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; border-radius: 2px; margin-bottom: 0.75rem;">
                SUPPORT & INQUIRIES
            </div>
            <h1 style="font-size: 2.25rem; font-weight: 900; letter-spacing: -0.02em; margin-bottom: 0.5rem;">
                Contact Qidex Logistics
            </h1>
            <p style="color: #94A3B8; max-width: 600px; font-size: 1rem;">
                Get in touch with our 24/7 global support team or visit our regional logistics headquarters.
            </p>
        </div>
    </div>

    <div class="section-padding" style="background-color: #F8FAFC;">
        <div class="container">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 2.5rem;">
                
                <!-- Contact Details Column -->
                <div>
                    <h2 style="font-size: 1.5rem; font-weight: 800; color: #0F172A; margin-bottom: 1.5rem;">
                        Global Headquarters
                    </h2>

                    <div style="display: flex; flex-direction: column; gap: 1.25rem; margin-bottom: 2rem;">
                        
                        <div style="display: flex; gap: 1rem; align-items: flex-start;">
                            <div style="width: 40px; height: 40px; background: #2563EB; color: #FFF; border-radius: 4px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                            </div>
                            <div>
                                <strong style="display: block; font-size: 0.95rem; color: #0F172A;">Corporate Address</strong>
                                <span style="font-size: 0.9rem; color: #64748B;"><?php echo esc_html(haivora_logistics_address()); ?></span>
                            </div>
                        </div>

                        <div style="display: flex; gap: 1rem; align-items: flex-start;">
                            <div style="width: 40px; height: 40px; background: #2563EB; color: #FFF; border-radius: 4px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                            </div>
                            <div>
                                <strong style="display: block; font-size: 0.95rem; color: #0F172A;">Customer Hotline</strong>
                                <span style="font-size: 0.9rem; color: #64748B;"><?php echo esc_html(haivora_logistics_phone()); ?></span>
                            </div>
                        </div>

                        <div style="display: flex; gap: 1rem; align-items: flex-start;">
                            <div style="width: 40px; height: 40px; background: #2563EB; color: #FFF; border-radius: 4px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                            </div>
                            <div>
                                <strong style="display: block; font-size: 0.95rem; color: #0F172A;">Support Email</strong>
                                <span style="font-size: 0.9rem; color: #64748B;"><?php echo esc_html(haivora_logistics_email()); ?></span>
                            </div>
                        </div>

                        <div style="display: flex; gap: 1rem; align-items: flex-start;">
                            <div style="width: 40px; height: 40px; background: #0F172A; color: #FFF; border-radius: 4px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                            </div>
                            <div>
                                <strong style="display: block; font-size: 0.95rem; color: #0F172A;">Business Hours</strong>
                                <span style="font-size: 0.85rem; color: #64748B; display: block;">Mon - Fri: 08:00 - 20:00 EST</span>
                                <span style="font-size: 0.85rem; color: #64748B; display: block;">Sat: 09:00 - 16:00 EST</span>
                                <span style="font-size: 0.85rem; color: #10B981; font-weight: 700; display: block;">Sun: 24/7 Urgent Dispatch Active</span>
                            </div>
                        </div>

                    </div>

                    <!-- Direct WhatsApp CTA Button -->
                    <div style="margin-bottom: 2rem;">
                        <a href="<?php echo esc_url($wa_link); ?>" target="_blank" rel="noopener" class="btn" style="background-color: #25D366; color: #FFFFFF; font-weight: 800; padding: 0.9rem 1.5rem; display: inline-flex; align-items: center; gap: 0.5rem; border-radius: 4px; text-decoration: none;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                            CHAT WITH US ON WHATSAPP
                        </a>
                    </div>

                    <!-- Interactive Map Visual Container -->
                    <div style="background-color: #E2E8F0; border-radius: 6px; height: 180px; position: relative; overflow: hidden; display: flex; align-items: center; justify-content: center; border: 1px solid #CBD5E1;">
                        <div style="position: absolute; inset: 0; opacity: 0.15; background-image: radial-gradient(#0F172A 1px, transparent 1px); background-size: 16px 16px;"></div>
                        <div style="text-align: center; z-index: 2; padding: 1rem;">
                            <strong style="font-size: 1rem; color: #0F172A; display: block;">Logistics Hub Headquarters Map</strong>
                            <span style="font-size: 0.8rem; color: #64748B;">100 Global Trade Parkway, NY 10001</span>
                        </div>
                    </div>

                </div>

                <!-- Contact Form Column -->
                <div style="background: #FFFFFF; padding: 2.5rem; border-radius: 6px; border: 1px solid var(--border-color); box-shadow: var(--shadow-md);">
                    <h2 style="font-size: 1.5rem; font-weight: 800; color: #0F172A; margin-bottom: 0.5rem;">
                        Send Us a Message
                    </h2>
                    <p style="font-size: 0.9rem; color: #64748B; margin-bottom: 1.5rem;">
                        Fill in your details below and our dispatch team will respond within 2 hours.
                    </p>

                    <div id="contact-alert-box" style="display: none; padding: 0.85rem 1rem; border-radius: 4px; font-weight: 600; margin-bottom: 1.25rem; font-size: 0.9rem;"></div>

                    <form id="contact-form" action="" method="post">
                        <?php if (function_exists('wp_nonce_field')) { wp_nonce_field('haivora_contact_action', 'haivora_contact_nonce'); } ?>
                        <input type="text" name="hp_contact_check" id="hp_contact_check" style="display:none !important;" tabindex="-1" autocomplete="off">

                        <div style="margin-bottom: 1.25rem;">
                            <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 0.35rem;">Your Full Name *</label>
                            <input type="text" name="full_name" id="c_full_name" required placeholder="e.g. Jane Smith" style="width: 100%; padding: 0.75rem; border: 1px solid #CBD5E1; border-radius: 4px;">
                        </div>

                        <div style="margin-bottom: 1.25rem;">
                            <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 0.35rem;">Email Address *</label>
                            <input type="email" name="email" id="c_email" required placeholder="e.g. jane@company.com" style="width: 100%; padding: 0.75rem; border: 1px solid #CBD5E1; border-radius: 4px;">
                        </div>

                        <div style="margin-bottom: 1.25rem;">
                            <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 0.35rem;">Phone Number</label>
                            <input type="tel" name="phone" id="c_phone" placeholder="e.g. +1 (555) 019-2834" style="width: 100%; padding: 0.75rem; border: 1px solid #CBD5E1; border-radius: 4px;">
                        </div>

                        <div style="margin-bottom: 1.25rem;">
                            <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 0.35rem;">Subject / Inquiry Type *</label>
                            <input type="text" name="subject" id="c_subject" required placeholder="e.g. Customs Clearance Inquiry" style="width: 100%; padding: 0.75rem; border: 1px solid #CBD5E1; border-radius: 4px;">
                        </div>

                        <div style="margin-bottom: 1.5rem;">
                            <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 0.35rem;">Your Message *</label>
                            <textarea name="message" id="c_message" rows="5" required placeholder="Type your message or cargo details here..." style="width: 100%; padding: 0.75rem; border: 1px solid #CBD5E1; border-radius: 4px;"></textarea>
                        </div>

                        <button type="submit" id="btn-submit-contact" class="btn btn-primary" style="width: 100%; padding: 0.9rem; font-size: 0.95rem;">
                            SEND MESSAGE
                        </button>
                    </form>

                </div>

            </div>
        </div>
    </div>

</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contact-form');
    const alertBox = document.getElementById('contact-alert-box');
    const submitBtn = document.getElementById('btn-submit-contact');

    if (!form) return;

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        alertBox.style.display = 'none';

        // Honeypot check
        const hp = document.getElementById('hp_contact_check').value;
        if (hp) {
            alertBox.style.display = 'block';
            alertBox.style.background = '#FEF2F2';
            alertBox.style.color = '#991B1B';
            alertBox.textContent = 'Spam detected.';
            return;
        }

        const payload = {
            full_name: document.getElementById('c_full_name').value.trim(),
            email: document.getElementById('c_email').value.trim(),
            phone: document.getElementById('c_phone').value.trim(),
            subject: document.getElementById('c_subject').value.trim(),
            message: document.getElementById('c_message').value.trim()
        };

        submitBtn.disabled = true;
        submitBtn.textContent = 'SENDING MESSAGE...';

        try {
            const res = await fetch('/api/contact', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });

            const data = await res.json();

            if (res.ok && data.success) {
                alertBox.style.display = 'block';
                alertBox.style.background = '#F0FDF4';
                alertBox.style.color = '#166534';
                alertBox.style.border = '1px solid #86EFAC';
                alertBox.innerHTML = '<strong>Thank you!</strong> Your message has been sent successfully (Reference: ' + data.contact.id + '). We will reply shortly.';
                form.reset();
            } else {
                alertBox.style.display = 'block';
                alertBox.style.background = '#FEF2F2';
                alertBox.style.color = '#991B1B';
                alertBox.textContent = data.error || 'Failed to send message. Please review inputs.';
            }
        } catch(err) {
            alertBox.style.display = 'block';
            alertBox.style.background = '#FEF2F2';
            alertBox.style.color = '#991B1B';
            alertBox.textContent = 'Error sending message. Please try again.';
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = 'SEND MESSAGE';
        }
    });
});
</script>

<?php
get_footer();
