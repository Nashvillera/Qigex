<?php
/**
 * Template Name: Services Page
 * Page Template for /services/
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
    <div style="background-color: #0F172A; color: #FFFFFF; padding: 4rem 0 3.5rem; border-bottom: 1px solid #1E293B;">
        <div class="container">
            <div style="display: inline-block; padding: 0.25rem 0.75rem; background: rgba(37, 99, 235, 0.2); color: #38BDF8; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; border-radius: 2px; margin-bottom: 0.75rem;">
                LOGISTICS CAPABILITIES
            </div>
            <h1 style="font-size: 2.5rem; font-weight: 900; letter-spacing: -0.02em; margin-bottom: 0.5rem;">
                Our Logistics Services
            </h1>
            <p style="color: #94A3B8; max-width: 650px; font-size: 1.05rem;">
                Specialized air, ocean, overland, courier, warehousing, and customs brokerage solutions tailored to global supply chains.
            </p>
        </div>
    </div>

    <!-- 9 Detailed Service Sections -->
    <div class="section-padding" style="background-color: #FFFFFF;">
        <div class="container">
            <div style="display: flex; flex-direction: column; gap: 3.5rem;">

                <!-- 1. Air Freight -->
                <div id="air-freight" style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 6px; padding: 2.5rem;">
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                        <div style="width: 48px; height: 48px; background: #2563EB; color: #FFFFFF; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3.5c-.5-.5-2.5 0-4 1.5L13.5 8.5 5.3 6.7c-.8-.2-1.6.1-2.1.8l-.4.6 5.3 3.8-3.1 3.1-2.2-.6-1 .5 2.1 2.1 2.1 2.1.5-1-.6-2.2 3.1-3.1 3.8 5.3.6-.4c.7-.5 1-1.3.8-2.1z"></path></svg>
                        </div>
                        <h2 style="font-size: 1.75rem; font-weight: 800; color: #0F172A;">Air Freight Services</h2>
                    </div>
                    <p style="color: #475569; line-height: 1.7; margin-bottom: 1.25rem;">
                        Priority global air cargo handling for time-critical commercial shipments, oversized industrial parts, and temperature-sensitive pharmaceutical goods with scheduled flight departures.
                    </p>
                    <h4 style="font-size: 0.95rem; font-weight: 800; color: #0F172A; text-transform: uppercase; margin-bottom: 0.5rem;">Key Benefits:</h4>
                    <ul style="color: #64748B; font-size: 0.9rem; line-height: 1.8; margin-bottom: 1.5rem; list-style-type: square; padding-left: 1.25rem;">
                        <li>Express door-to-airport and door-to-door transit options</li>
                        <li>Temperature-monitored refrigerated cargo holds</li>
                        <li>Full flight charter availability for heavy volume shipments</li>
                    </ul>
                    <a href="<?php echo esc_url(home_url('/get-a-quote/?service=air-freight')); ?>" class="btn btn-primary">REQUEST AIR FREIGHT QUOTE</a>
                </div>

                <!-- 2. Sea Freight -->
                <div id="sea-freight" style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 6px; padding: 2.5rem;">
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                        <div style="width: 48px; height: 48px; background: #2563EB; color: #FFFFFF; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 20a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8l-7-5-5 3.5L3 3v17z"></path><path d="M2 12h20"></path></svg>
                        </div>
                        <h2 style="font-size: 1.75rem; font-weight: 800; color: #0F172A;">Sea / Ocean Freight</h2>
                    </div>
                    <p style="color: #475569; line-height: 1.7; margin-bottom: 1.25rem;">
                        High-capacity ocean shipping connecting major global container ports with full container loads (FCL), consolidated less-than-container loads (LCL), and roll-on/roll-off vessel cargo.
                    </p>
                    <h4 style="font-size: 0.95rem; font-weight: 800; color: #0F172A; text-transform: uppercase; margin-bottom: 0.5rem;">Key Benefits:</h4>
                    <ul style="color: #64748B; font-size: 0.9rem; line-height: 1.8; margin-bottom: 1.5rem; list-style-type: square; padding-left: 1.25rem;">
                        <li>Maximum cost efficiency for high-tonnage international shipments</li>
                        <li>Comprehensive port handling and bonded storage</li>
                        <li>Direct carrier contracts across major Pacific & Atlantic trade lanes</li>
                    </ul>
                    <a href="<?php echo esc_url(home_url('/get-a-quote/?service=sea-freight')); ?>" class="btn btn-primary">REQUEST SEA FREIGHT QUOTE</a>
                </div>

                <!-- 3. Road Freight -->
                <div id="road-freight" style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 6px; padding: 2.5rem;">
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                        <div style="width: 48px; height: 48px; background: #2563EB; color: #FFFFFF; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                        </div>
                        <h2 style="font-size: 1.75rem; font-weight: 800; color: #0F172A;">Road & Overland Transport</h2>
                    </div>
                    <p style="color: #475569; line-height: 1.7; margin-bottom: 1.25rem;">
                        Reliable cross-border highway trucking network providing Full Truckload (FTL) and Less Than Truckload (LTL) services equipped with satellite telematics.
                    </p>
                    <h4 style="font-size: 0.95rem; font-weight: 800; color: #0F172A; text-transform: uppercase; margin-bottom: 0.5rem;">Key Benefits:</h4>
                    <ul style="color: #64748B; font-size: 0.9rem; line-height: 1.8; margin-bottom: 1.5rem; list-style-type: square; padding-left: 1.25rem;">
                        <li>GPS-monitored vehicle fleets with driver communication</li>
                        <li>Specialized refrigerated trailers and flatbed heavy haulage</li>
                        <li>Seamless border crossing handling and customs transit documentation</li>
                    </ul>
                    <a href="<?php echo esc_url(home_url('/get-a-quote/?service=road-freight')); ?>" class="btn btn-primary">REQUEST ROAD FREIGHT QUOTE</a>
                </div>

                <!-- 4. Express Courier -->
                <div id="express" style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 6px; padding: 2.5rem;">
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                        <div style="width: 48px; height: 48px; background: #2563EB; color: #FFFFFF; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                        </div>
                        <h2 style="font-size: 1.75rem; font-weight: 800; color: #0F172A;">Express Courier Delivery</h2>
                    </div>
                    <p style="color: #475569; line-height: 1.7; margin-bottom: 1.25rem;">
                        Rapid door-to-door courier service for urgent legal documents, medical samples, and lightweight commercial packages requiring guaranteed transit timelines.
                    </p>
                    <h4 style="font-size: 0.95rem; font-weight: 800; color: #0F172A; text-transform: uppercase; margin-bottom: 0.5rem;">Key Benefits:</h4>
                    <ul style="color: #64748B; font-size: 0.9rem; line-height: 1.8; margin-bottom: 1.5rem; list-style-type: square; padding-left: 1.25rem;">
                        <li>Next-day international express shipping to major business capitals</li>
                        <li>Real-time SMS & email status updates</li>
                        <li>Digital recipient signature proof of delivery (POD)</li>
                    </ul>
                    <a href="<?php echo esc_url(home_url('/get-a-quote/?service=express')); ?>" class="btn btn-primary">BOOK EXPRESS COURIER</a>
                </div>

                <!-- 5. International Shipping -->
                <div id="international" style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 6px; padding: 2.5rem;">
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                        <div style="width: 48px; height: 48px; background: #2563EB; color: #FFFFFF; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                        </div>
                        <h2 style="font-size: 1.75rem; font-weight: 800; color: #0F172A;">International Shipping</h2>
                    </div>
                    <p style="color: #475569; line-height: 1.7; margin-bottom: 1.25rem;">
                        Integrated global trade solutions combining ocean, air, and road legs into a single multimodal booking across 120+ countries worldwide.
                    </p>
                    <h4 style="font-size: 0.95rem; font-weight: 800; color: #0F172A; text-transform: uppercase; margin-bottom: 0.5rem;">Key Benefits:</h4>
                    <ul style="color: #64748B; font-size: 0.9rem; line-height: 1.8; margin-bottom: 1.5rem; list-style-type: square; padding-left: 1.25rem;">
                        <li>Single-point account manager for end-to-end logistics</li>
                        <li>Global tariff and customs compliance assistance</li>
                        <li>All-inclusive tracking telemetry through the Qidex Portal</li>
                    </ul>
                    <a href="<?php echo esc_url(home_url('/get-a-quote/?service=international')); ?>" class="btn btn-primary">REQUEST INTERNATIONAL QUOTE</a>
                </div>

                <!-- 6. Warehousing & Fulfillment -->
                <div id="warehousing" style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 6px; padding: 2.5rem;">
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                        <div style="width: 48px; height: 48px; background: #2563EB; color: #FFFFFF; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                        </div>
                        <h2 style="font-size: 1.75rem; font-weight: 800; color: #0F172A;">Warehousing & Storage</h2>
                    </div>
                    <p style="color: #475569; line-height: 1.7; margin-bottom: 1.25rem;">
                        Secure bonded fulfillment centers equipped with automated inventory barcode management, climate-controlled storage zones, and cross-docking facilities.
                    </p>
                    <h4 style="font-size: 0.95rem; font-weight: 800; color: #0F172A; text-transform: uppercase; margin-bottom: 0.5rem;">Key Benefits:</h4>
                    <ul style="color: #64748B; font-size: 0.9rem; line-height: 1.8; margin-bottom: 1.5rem; list-style-type: square; padding-left: 1.25rem;">
                        <li>24/7 CCTV monitored secure facilities</li>
                        <li>Real-time digital inventory stock management</li>
                        <li>Flexible short-term and long-term pallet storage options</li>
                    </ul>
                    <a href="<?php echo esc_url(home_url('/get-a-quote/?service=warehousing')); ?>" class="btn btn-primary">REQUEST WAREHOUSING QUOTE</a>
                </div>

                <!-- 7. Customs Clearance -->
                <div id="customs" style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 6px; padding: 2.5rem;">
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                        <div style="width: 48px; height: 48px; background: #2563EB; color: #FFFFFF; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                        </div>
                        <h2 style="font-size: 1.75rem; font-weight: 800; color: #0F172A;">Customs Brokerage & Clearance</h2>
                    </div>
                    <p style="color: #475569; line-height: 1.7; margin-bottom: 1.25rem;">
                        Licensed customs clearance agents handling tariff classifications, duty payment management, import/export permits, and port inspection documentation.
                    </p>
                    <h4 style="font-size: 0.95rem; font-weight: 800; color: #0F172A; text-transform: uppercase; margin-bottom: 0.5rem;">Key Benefits:</h4>
                    <ul style="color: #64748B; font-size: 0.9rem; line-height: 1.8; margin-bottom: 1.5rem; list-style-type: square; padding-left: 1.25rem;">
                        <li>Rapid clearance turnaround preventing costly port demurrage fees</li>
                        <li>Expert guidance on international HS tariff code classification</li>
                        <li>Direct electronic submission to government customs portals</li>
                    </ul>
                    <a href="<?php echo esc_url(home_url('/get-a-quote/?service=customs')); ?>" class="btn btn-primary">REQUEST BROKERAGE QUOTE</a>
                </div>

                <!-- 8. Last-Mile Delivery -->
                <div id="last-mile" style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 6px; padding: 2.5rem;">
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                        <div style="width: 48px; height: 48px; background: #2563EB; color: #FFFFFF; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                        </div>
                        <h2 style="font-size: 1.75rem; font-weight: 800; color: #0F172A;">Last-Mile Delivery</h2>
                    </div>
                    <p style="color: #475569; line-height: 1.7; margin-bottom: 1.25rem;">
                        Final-stage urban distribution connecting regional hubs directly to residential or commercial customer doorsteps.
                    </p>
                    <h4 style="font-size: 0.95rem; font-weight: 800; color: #0F172A; text-transform: uppercase; margin-bottom: 0.5rem;">Key Benefits:</h4>
                    <ul style="color: #64748B; font-size: 0.9rem; line-height: 1.8; margin-bottom: 1.5rem; list-style-type: square; padding-left: 1.25rem;">
                        <li>Recipient delivery scheduling and live driver map tracking</li>
                        <li>High first-attempt successful delivery rates</li>
                        <li>Electronic signature and photo proof of delivery</li>
                    </ul>
                    <a href="<?php echo esc_url(home_url('/get-a-quote/?service=last-mile')); ?>" class="btn btn-primary">BOOK LAST-MILE DELIVERY</a>
                </div>

                <!-- 9. E-commerce Fulfillment -->
                <div id="e-commerce" style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 6px; padding: 2.5rem;">
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                        <div style="width: 48px; height: 48px; background: #2563EB; color: #FFFFFF; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                        </div>
                        <h2 style="font-size: 1.75rem; font-weight: 800; color: #0F172A;">E-commerce Fulfillment</h2>
                    </div>
                    <p style="color: #475569; line-height: 1.7; margin-bottom: 1.25rem;">
                        Complete 3PL logistics framework for digital storefronts including pick-and-pack, branded packaging, automated return logistics, and inventory API integration.
                    </p>
                    <h4 style="font-size: 0.95rem; font-weight: 800; color: #0F172A; text-transform: uppercase; margin-bottom: 0.5rem;">Key Benefits:</h4>
                    <ul style="color: #64748B; font-size: 0.9rem; line-height: 1.8; margin-bottom: 1.5rem; list-style-type: square; padding-left: 1.25rem;">
                        <li>Direct API connectivity with WooCommerce, Shopify, and custom stores</li>
                        <li>Same-day order dispatch for early afternoon orders</li>
                        <li>Automated reverse logistics and returns processing</li>
                    </ul>
                    <a href="<?php echo esc_url(home_url('/get-a-quote/?service=ecommerce')); ?>" class="btn btn-primary">START E-COMMERCE FULFILLMENT</a>
                </div>

            </div>
        </div>
    </div>

</main>

<?php
get_footer();
