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
                RATE CALCULATOR
            </div>
            <h1 style="font-size: 2.25rem; font-weight: 900; letter-spacing: -0.02em; margin-bottom: 0.5rem;">
                Request a Freight Quote
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
                
                <form id="quote-request-form" action="" method="post" onsubmit="event.preventDefault(); alert('Thank you for requesting a quote! Form submission processing will be connected in Phase 6.'); return false;">
                    
                    <h3 style="font-size: 1.1rem; font-weight: 800; color: #0F172A; text-transform: uppercase; border-bottom: 2px solid #E2E8F0; padding-bottom: 0.5rem; margin-bottom: 1.5rem;">
                        1. Contact Details
                    </h3>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.25rem; margin-bottom: 2rem;">
                        <div>
                            <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 0.35rem;">Full Name *</label>
                            <input type="text" required placeholder="e.g. John Doe" style="width: 100%; padding: 0.75rem; border: 1px solid #CBD5E1; border-radius: 4px;">
                        </div>

                        <div>
                            <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 0.35rem;">Email Address *</label>
                            <input type="email" required placeholder="e.g. john@company.com" style="width: 100%; padding: 0.75rem; border: 1px solid #CBD5E1; border-radius: 4px;">
                        </div>

                        <div>
                            <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 0.35rem;">Phone Number *</label>
                            <input type="tel" required placeholder="e.g. +1 (555) 019-2834" style="width: 100%; padding: 0.75rem; border: 1px solid #CBD5E1; border-radius: 4px;">
                        </div>
                    </div>

                    <h3 style="font-size: 1.1rem; font-weight: 800; color: #0F172A; text-transform: uppercase; border-bottom: 2px solid #E2E8F0; padding-bottom: 0.5rem; margin-bottom: 1.5rem;">
                        2. Route & Cargo Specifications
                    </h3>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.25rem; margin-bottom: 2rem;">
                        <div>
                            <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 0.35rem;">Origin (City / Country) *</label>
                            <input type="text" required placeholder="e.g. New York, USA" style="width: 100%; padding: 0.75rem; border: 1px solid #CBD5E1; border-radius: 4px;">
                        </div>

                        <div>
                            <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 0.35rem;">Destination (City / Country) *</label>
                            <input type="text" required placeholder="e.g. Frankfurt, Germany" style="width: 100%; padding: 0.75rem; border: 1px solid #CBD5E1; border-radius: 4px;">
                        </div>

                        <div>
                            <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 0.35rem;">Shipment Type *</label>
                            <select required style="width: 100%; padding: 0.75rem; border: 1px solid #CBD5E1; border-radius: 4px; background: #FFF;">
                                <option value="">Select Shipment Type</option>
                                <option value="air">Air Freight</option>
                                <option value="sea">Sea Freight (FCL / LCL)</option>
                                <option value="road">Road Freight Overland</option>
                                <option value="express">Express Courier Parcel</option>
                            </select>
                        </div>

                        <div>
                            <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 0.35rem;">Package Type *</label>
                            <select required style="width: 100%; padding: 0.75rem; border: 1px solid #CBD5E1; border-radius: 4px; background: #FFF;">
                                <option value="">Select Package Type</option>
                                <option value="pallet">Pallets</option>
                                <option value="container">Shipping Container</option>
                                <option value="parcel">Box / Parcel</option>
                                <option value="document">Envelope / Document</option>
                            </select>
                        </div>

                        <div>
                            <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 0.35rem;">Total Weight (kg) *</label>
                            <input type="number" required placeholder="e.g. 150" style="width: 100%; padding: 0.75rem; border: 1px solid #CBD5E1; border-radius: 4px;">
                        </div>

                        <div>
                            <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 0.35rem;">Dimensions (L x W x H cm)</label>
                            <input type="text" placeholder="e.g. 120 x 80 x 100 cm" style="width: 100%; padding: 0.75rem; border: 1px solid #CBD5E1; border-radius: 4px;">
                        </div>

                        <div>
                            <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 0.35rem;">Preferred Shipping Method</label>
                            <select style="width: 100%; padding: 0.75rem; border: 1px solid #CBD5E1; border-radius: 4px; background: #FFF;">
                                <option value="standard">Standard Priority (Default)</option>
                                <option value="express">Urgent Express</option>
                                <option value="economy">Economy Saver</option>
                            </select>
                        </div>

                        <div>
                            <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 0.35rem;">Pickup Date</label>
                            <input type="date" style="width: 100%; padding: 0.75rem; border: 1px solid #CBD5E1; border-radius: 4px;">
                        </div>
                    </div>

                    <div style="margin-bottom: 2rem;">
                        <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 0.35rem;">Additional Information / Special Handling Instructions</label>
                        <textarea rows="4" placeholder="Mention temperature sensitivity, dangerous goods classification, customs requirements, etc..." style="width: 100%; padding: 0.75rem; border: 1px solid #CBD5E1; border-radius: 4px;"></textarea>
                    </div>

                    <div>
                        <button type="submit" class="btn btn-primary" style="padding: 1rem 2.5rem; font-size: 1rem; width: 100%;">
                            REQUEST QUOTE
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

</main>

<?php
get_footer();
