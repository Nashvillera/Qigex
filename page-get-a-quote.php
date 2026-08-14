<?php
/**
 * Template Name: Get a Quote Page
 * Page Template for /get-a-quote/
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
                FREIGHT RATE CALCULATOR
            </div>
            <h1 style="font-size: 2.25rem; font-weight: 900; letter-spacing: -0.02em; margin-bottom: 0.5rem;">
                Request an Official Freight Quote
            </h1>
            <p style="color: #94A3B8; max-width: 600px; font-size: 1rem;">
                Submit your cargo specifications below for an all-inclusive shipping estimate with zero hidden terminal fees.
            </p>
        </div>
    </div>

    <!-- Quote Form Container -->
    <div class="section-padding" style="background-color: #F8FAFC;">
        <div class="container" style="max-width: 900px;">
            <div style="background: #FFFFFF; border-radius: 6px; padding: 2.5rem; box-shadow: var(--shadow-lg); border: 1px solid var(--border-color);">
                
                <div id="quote-feedback-alert" style="display: none; padding: 1rem 1.25rem; border-radius: 4px; font-weight: 600; margin-bottom: 1.5rem; font-size: 0.95rem;"></div>

                <div id="quote-success-panel" style="display: none; background: #F0FDF4; border: 1px solid #86EFAC; border-radius: 6px; padding: 2rem; text-align: center;">
                    <div style="width: 50px; height: 50px; background: #16A34A; color: #FFF; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 24px; margin-bottom: 1rem;">✓</div>
                    <h2 style="font-size: 1.5rem; font-weight: 900; color: #14532D; margin-bottom: 0.5rem;">Quote Request Received!</h2>
                    <p style="color: #166534; max-width: 600px; margin: 0 auto 1.5rem;">Your request has been logged and assigned reference code <strong id="res-quote-id" style="color: #15803D; font-size: 1.1rem;"></strong>. An email confirmation has been sent to <span id="res-quote-email" style="font-weight: 700;"></span>.</p>
                    
                    <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                        <a href="/dashboard" class="btn btn-primary" style="padding: 0.75rem 1.5rem; font-size: 0.9rem;">View in Customer Portal</a>
                        <a id="res-wa-link" href="#" target="_blank" class="btn" style="background: #25D366; color: #FFF; font-weight: 800; padding: 0.75rem 1.5rem; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 0.5rem;">
                            Chat via WhatsApp
                        </a>
                    </div>
                </div>

                <form id="quote-request-form" action="" method="post">
                    <?php if (function_exists('wp_nonce_field')) { wp_nonce_field('haivora_quote_action', 'haivora_quote_nonce'); } ?>
                    
                    <!-- Honeypot Field (Spam Protection) -->
                    <input type="text" name="hp_check" id="hp_check" style="display:none !important;" tabindex="-1" autocomplete="off">

                    <h3 style="font-size: 1.1rem; font-weight: 800; color: #0F172A; text-transform: uppercase; border-bottom: 2px solid #E2E8F0; padding-bottom: 0.5rem; margin-bottom: 1.5rem;">
                        1. Contact Details
                    </h3>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.25rem; margin-bottom: 2rem;">
                        <div>
                            <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 0.35rem;">Full Name *</label>
                            <input type="text" name="full_name" id="q_full_name" required placeholder="e.g. John Doe" style="width: 100%; padding: 0.75rem; border: 1px solid #CBD5E1; border-radius: 4px;">
                        </div>

                        <div>
                            <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 0.35rem;">Email Address *</label>
                            <input type="email" name="email" id="q_email" required placeholder="e.g. john@company.com" style="width: 100%; padding: 0.75rem; border: 1px solid #CBD5E1; border-radius: 4px;">
                        </div>

                        <div>
                            <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 0.35rem;">Phone Number *</label>
                            <input type="tel" name="phone" id="q_phone" required placeholder="e.g. +1 (555) 019-2834" style="width: 100%; padding: 0.75rem; border: 1px solid #CBD5E1; border-radius: 4px;">
                        </div>
                    </div>

                    <h3 style="font-size: 1.1rem; font-weight: 800; color: #0F172A; text-transform: uppercase; border-bottom: 2px solid #E2E8F0; padding-bottom: 0.5rem; margin-bottom: 1.5rem;">
                        2. Route & Cargo Specifications
                    </h3>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.25rem; margin-bottom: 2rem;">
                        <div>
                            <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 0.35rem;">Origin (City / Country) *</label>
                            <input type="text" name="origin" id="q_origin" required placeholder="e.g. New York, USA" style="width: 100%; padding: 0.75rem; border: 1px solid #CBD5E1; border-radius: 4px;">
                        </div>

                        <div>
                            <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 0.35rem;">Destination (City / Country) *</label>
                            <input type="text" name="destination" id="q_destination" required placeholder="e.g. Frankfurt, Germany" style="width: 100%; padding: 0.75rem; border: 1px solid #CBD5E1; border-radius: 4px;">
                        </div>

                        <div>
                            <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 0.35rem;">Shipment Type *</label>
                            <select name="shipment_type" id="q_shipment_type" required style="width: 100%; padding: 0.75rem; border: 1px solid #CBD5E1; border-radius: 4px; background: #FFF;">
                                <option value="">Select Shipment Type</option>
                                <option value="Air Freight">Air Freight Express</option>
                                <option value="Sea Freight (FCL / LCL)">Sea Freight Container (FCL / LCL)</option>
                                <option value="Road Freight">Road Overland Transport</option>
                                <option value="Express Courier">Express Courier Parcel</option>
                            </select>
                        </div>

                        <div>
                            <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 0.35rem;">Package Type *</label>
                            <select name="package_type" id="q_package_type" required style="width: 100%; padding: 0.75rem; border: 1px solid #CBD5E1; border-radius: 4px; background: #FFF;">
                                <option value="">Select Package Type</option>
                                <option value="Pallets">Pallets / Wooden Crates</option>
                                <option value="Container">Full Shipping Container (20ft / 40ft)</option>
                                <option value="Box / Parcel">Carton Boxes / Loose Parcels</option>
                                <option value="Document">Envelope / Courier Document</option>
                            </select>
                        </div>

                        <div>
                            <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 0.35rem;">Total Weight (kg) *</label>
                            <input type="number" name="weight" id="q_weight" required min="0.1" step="0.1" placeholder="e.g. 150" style="width: 100%; padding: 0.75rem; border: 1px solid #CBD5E1; border-radius: 4px;">
                        </div>

                        <div>
                            <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 0.35rem;">Dimensions (L x W x H cm)</label>
                            <input type="text" name="dimensions" id="q_dimensions" placeholder="e.g. 120 x 80 x 100 cm" style="width: 100%; padding: 0.75rem; border: 1px solid #CBD5E1; border-radius: 4px;">
                        </div>

                        <div>
                            <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 0.35rem;">Preferred Shipping Method</label>
                            <select name="shipping_method" id="q_shipping_method" style="width: 100%; padding: 0.75rem; border: 1px solid #CBD5E1; border-radius: 4px; background: #FFF;">
                                <option value="Standard Priority">Standard Priority (Default)</option>
                                <option value="Urgent Express">Urgent Express Air</option>
                                <option value="Economy Saver">Economy Saver Logistics</option>
                            </select>
                        </div>

                        <div>
                            <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 0.35rem;">Pickup Date</label>
                            <input type="date" name="pickup_date" id="q_pickup_date" value="<?php echo date('Y-m-d'); ?>" style="width: 100%; padding: 0.75rem; border: 1px solid #CBD5E1; border-radius: 4px;">
                        </div>
                    </div>

                    <div style="margin-bottom: 2rem;">
                        <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 0.35rem;">Additional Information / Special Handling Instructions</label>
                        <textarea name="additional_info" id="q_additional_info" rows="4" placeholder="Mention temperature sensitivity, dangerous goods classification, customs requirements, etc..." style="width: 100%; padding: 0.75rem; border: 1px solid #CBD5E1; border-radius: 4px;"></textarea>
                    </div>

                    <div>
                        <button type="submit" id="btn-submit-quote" class="btn btn-primary" style="padding: 1rem 2.5rem; font-size: 1rem; width: 100%;">
                            SUBMIT QUOTE REQUEST
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('quote-request-form');
    const alertBox = document.getElementById('quote-feedback-alert');
    const successPanel = document.getElementById('quote-success-panel');
    const submitBtn = document.getElementById('btn-submit-quote');

    if (!form) return;

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        alertBox.style.display = 'none';

        // Check Honeypot
        const hp = document.getElementById('hp_check').value;
        if (hp) {
            alertBox.style.display = 'block';
            alertBox.style.background = '#FEF2F2';
            alertBox.style.color = '#991B1B';
            alertBox.textContent = 'Spam submission detected.';
            return;
        }

        const payload = {
            full_name: document.getElementById('q_full_name').value.trim(),
            email: document.getElementById('q_email').value.trim(),
            phone: document.getElementById('q_phone').value.trim(),
            origin: document.getElementById('q_origin').value.trim(),
            destination: document.getElementById('q_destination').value.trim(),
            shipment_type: document.getElementById('q_shipment_type').value,
            package_type: document.getElementById('q_package_type').value,
            weight: document.getElementById('q_weight').value,
            dimensions: document.getElementById('q_dimensions').value.trim(),
            shipping_method: document.getElementById('q_shipping_method').value,
            pickup_date: document.getElementById('q_pickup_date').value,
            additional_info: document.getElementById('q_additional_info').value.trim()
        };

        submitBtn.disabled = true;
        submitBtn.textContent = 'PROCESSING QUOTE...';

        try {
            const res = await fetch('/api/quotes', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });

            const data = await res.json();

            if (res.ok && data.success) {
                form.style.display = 'none';
                successPanel.style.display = 'block';
                document.getElementById('res-quote-id').textContent = data.quote.id;
                document.getElementById('res-quote-email').textContent = data.quote.email;
                
                const waMessage = `Hello Qidex Logistics, I submitted a quote request (${data.quote.id}) from ${data.quote.origin} to ${data.quote.destination}.`;
                document.getElementById('res-wa-link').href = 'https://wa.me/18005557433?text=' + encodeURIComponent(waMessage);
            } else {
                alertBox.style.display = 'block';
                alertBox.style.background = '#FEF2F2';
                alertBox.style.color = '#991B1B';
                alertBox.textContent = data.error || 'Failed to submit quote request. Please review form inputs.';
            }
        } catch(err) {
            alertBox.style.display = 'block';
            alertBox.style.background = '#FEF2F2';
            alertBox.style.color = '#991B1B';
            alertBox.textContent = 'Network or server error while submitting quote request.';
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = 'SUBMIT QUOTE REQUEST';
        }
    });
});
</script>

<?php
get_footer();
