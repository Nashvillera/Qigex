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
                                <span style="font-size: 0.85rem; color: #10B981; font-weight: 700; display: block;">Sun: 24/7 Urgent Hotline Active</span>
                            </div>
                        </div>

                    </div>

                    <!-- Direct WhatsApp CTA Button -->
                    <div style="margin-bottom: 2rem;">
                        <a href="https://wa.me/18005557433" target="_blank" rel="noopener" class="btn" style="background-color: #25D366; color: #FFFFFF; font-weight: 800; padding: 0.9rem 1.5rem; display: inline-flex; align-items: center; gap: 0.5rem; border-radius: 4px;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                            CHAT ON WHATSAPP NOW
                        </a>
                    </div>

                    <!-- Interactive Map Visual Placeholder -->
                    <div style="background-color: #E2E8F0; border-radius: 6px; height: 200px; position: relative; overflow: hidden; display: flex; align-items: center; justify-content: center; border: 1px solid #CBD5E1;">
                        <div style="position: absolute; inset: 0; opacity: 0.15; background-image: radial-gradient(#0F172A 1px, transparent 1px); background-size: 16px 16px;"></div>
                        <div style="text-align: center; z-index: 2; padding: 1rem;">
                            <strong style="font-size: 1rem; color: #0F172A; display: block;">Interactive Map Visual Placeholder</strong>
                            <span style="font-size: 0.8rem; color: #64748B;">100 Global Trade Parkway, Logistics Hub, NY 10001</span>
                        </div>
                    </div>

                </div>

                <!-- Contact Form Placeholder Column -->
                <div style="background: #FFFFFF; padding: 2.5rem; border-radius: 6px; border: 1px solid var(--border-color); box-shadow: var(--shadow-md);">
                    <h2 style="font-size: 1.5rem; font-weight: 800; color: #0F172A; margin-bottom: 0.5rem;">
                        Send Us a Message
                    </h2>
                    <p style="font-size: 0.9rem; color: #64748B; margin-bottom: 1.5rem;">
                        Form processing and email dispatching will be completed in Phase 6.
                    </p>

                    <form id="contact-form" action="" method="post" onsubmit="event.preventDefault(); alert('Form processing will be completed in Phase 6.'); return false;">
                        
                        <div style="margin-bottom: 1.25rem;">
                            <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 0.35rem;">Your Full Name *</label>
                            <input type="text" required placeholder="e.g. Jane Smith" style="width: 100%; padding: 0.75rem; border: 1px solid #CBD5E1; border-radius: 4px;">
                        </div>

                        <div style="margin-bottom: 1.25rem;">
                            <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 0.35rem;">Email Address *</label>
                            <input type="email" required placeholder="e.g. jane@company.com" style="width: 100%; padding: 0.75rem; border: 1px solid #CBD5E1; border-radius: 4px;">
                        </div>

                        <div style="margin-bottom: 1.25rem;">
                            <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 0.35rem;">Subject / Department *</label>
                            <select style="width: 100%; padding: 0.75rem; border: 1px solid #CBD5E1; border-radius: 4px; background: #FFF;">
                                <option value="general">General Support & Tracking</option>
                                <option value="freight">Freight & Cargo Inquiry</option>
                                <option value="customs">Customs Brokerage Help</option>
                                <option value="billing">Billing & Invoicing</option>
                            </select>
                        </div>

                        <div style="margin-bottom: 1.5rem;">
                            <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 0.35rem;">Your Message *</label>
                            <textarea rows="5" required placeholder="Type your message or inquiry details here..." style="width: 100%; padding: 0.75rem; border: 1px solid #CBD5E1; border-radius: 4px;"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.9rem; font-size: 0.95rem;">
                            SEND MESSAGE
                        </button>
                    </form>

                </div>

            </div>
        </div>
    </div>

</main>

<?php
get_footer();
