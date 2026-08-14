<?php
/**
 * Template Name: Track Shipment Page
 * Page Template for /track-shipment/
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
                LIVE TELEMETRY PORTAL
            </div>
            <h1 style="font-size: 2.25rem; font-weight: 900; letter-spacing: -0.02em; margin-bottom: 0.5rem;">
                Track Your Shipment
            </h1>
            <p style="color: #94A3B8; max-width: 600px; font-size: 1rem;">
                Enter your Qidex Waybill, Parcel Tracking ID, or Container Reference number below to view real-time location and milestone status.
            </p>
        </div>
    </div>

    <!-- Tracking Container -->
    <div class="section-padding" style="background-color: #F8FAFC;">
        <div class="container">

            <div style="background: #FFFFFF; border-radius: 6px; padding: 2rem; box-shadow: var(--shadow-md); border: 1px solid var(--border-color); margin-bottom: 2rem;">
                <form id="page-tracking-form" action="" method="get" onsubmit="event.preventDefault(); return false;">
                    <label style="display: block; font-size: 0.85rem; font-weight: 800; text-transform: uppercase; color: #0F172A; letter-spacing: 0.05em; margin-bottom: 0.5rem;">
                        Waybill Number / Tracking ID
                    </label>
                    <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                        <input type="text" id="page-tracking-input" class="tracking-input" placeholder="e.g. QX-8829-US, QX-9912-DE, QX-3301-CN..." value="QX-8829-US" required style="flex-grow: 1; min-width: 280px; padding: 0.9rem 1.25rem; border: 2px solid #E2E8F0; border-radius: 4px; font-weight: 600; font-size: 1rem;">
                        <button type="button" id="btn-page-track" class="btn btn-primary" style="padding: 0.9rem 2rem;">
                            TRACK SHIPMENT
                        </button>
                    </div>
                </form>

                <div style="margin-top: 1rem; display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; font-size: 0.85rem; color: #64748B;">
                    <span>Sample Demonstration References:</span>
                    <button type="button" class="demo-pill" data-code="QX-8829-US">QX-8829-US (Air Cargo)</button>
                    <button type="button" class="demo-pill" data-code="QX-9912-DE">QX-9912-DE (Ocean Freight)</button>
                    <button type="button" class="demo-pill" data-code="QX-3301-CN">QX-3301-CN (Express Courier)</button>
                </div>
            </div>

            <!-- Complete Tracking Result Container -->
            <div id="tracking-detail-card" style="background: #FFFFFF; border-radius: 6px; padding: 2rem; box-shadow: var(--shadow-lg); border: 1px solid var(--border-color);">
                
                <div style="display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px solid #E2E8F0; padding-bottom: 1.5rem; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
                    <div>
                        <span style="font-size: 0.75rem; font-weight: 800; text-transform: uppercase; color: #94A3B8; letter-spacing: 0.08em; display: block;">SHIPMENT REFERENCE</span>
                        <h2 id="result-shipment-id" style="font-size: 1.75rem; font-weight: 900; color: #0F172A; font-family: var(--font-mono);">QX-8829-US</h2>
                        <span style="font-size: 0.85rem; color: #64748B; margin-top: 0.2rem; display: block;">Booked via Qidex Transatlantic Freight Hub</span>
                    </div>

                    <div>
                        <span style="font-size: 0.75rem; font-weight: 800; text-transform: uppercase; color: #94A3B8; letter-spacing: 0.08em; display: block; margin-bottom: 0.35rem;">CURRENT STATUS</span>
                        <span id="result-status-badge" class="status-badge in-transit" style="padding: 0.45rem 1.1rem; border-radius: 9999px; font-weight: 800; font-size: 0.85rem; background-color: rgba(37, 99, 235, 0.12); color: #2563EB; display: inline-block;">IN TRANSIT</span>
                    </div>
                </div>

                <!-- Comprehensive Prepared Display Area Requirements -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.25rem; background-color: #F8FAFC; padding: 1.5rem; border-radius: 4px; border: 1px solid #E2E8F0; margin-bottom: 2rem;">
                    <div>
                        <span style="font-size: 0.75rem; color: #64748B; font-weight: 700; text-transform: uppercase;">Origin</span>
                        <strong id="meta-origin" style="display: block; font-size: 1rem; color: #0F172A; margin-top: 0.2rem;">JFK, New York, USA</strong>
                    </div>
                    <div>
                        <span style="font-size: 0.75rem; color: #64748B; font-weight: 700; text-transform: uppercase;">Destination</span>
                        <strong id="meta-destination" style="display: block; font-size: 1rem; color: #0F172A; margin-top: 0.2rem;">FRA, Frankfurt, Germany</strong>
                    </div>
                    <div>
                        <span style="font-size: 0.75rem; color: #64748B; font-weight: 700; text-transform: uppercase;">Current Location</span>
                        <strong id="meta-location" style="display: block; font-size: 1rem; color: #2563EB; margin-top: 0.2rem;">Flight QX-702 (Mid-Atlantic)</strong>
                    </div>
                    <div>
                        <span style="font-size: 0.75rem; color: #64748B; font-weight: 700; text-transform: uppercase;">Estimated Delivery</span>
                        <strong id="meta-delivery" style="display: block; font-size: 1rem; color: #10B981; margin-top: 0.2rem;">Aug 14, 2026 - 16:30 GMT</strong>
                    </div>
                    <div>
                        <span style="font-size: 0.75rem; color: #64748B; font-weight: 700; text-transform: uppercase;">Shipment Date</span>
                        <strong id="meta-shipdate" style="display: block; font-size: 0.95rem; color: #0F172A; margin-top: 0.2rem;">Aug 12, 2026</strong>
                    </div>
                    <div>
                        <span style="font-size: 0.75rem; color: #64748B; font-weight: 700; text-transform: uppercase;">Last Update</span>
                        <strong id="meta-lastupdate" style="display: block; font-size: 0.95rem; color: #0F172A; margin-top: 0.2rem;">10 minutes ago</strong>
                    </div>
                    <div>
                        <span style="font-size: 0.75rem; color: #64748B; font-weight: 700; text-transform: uppercase;">Carrier</span>
                        <strong id="meta-carrier" style="display: block; font-size: 0.95rem; color: #0F172A; margin-top: 0.2rem;">Qidex Air Cargo Fleet</strong>
                    </div>
                    <div>
                        <span style="font-size: 0.75rem; color: #64748B; font-weight: 700; text-transform: uppercase;">Package Type</span>
                        <strong id="meta-packagetype" style="display: block; font-size: 0.95rem; color: #0F172A; margin-top: 0.2rem;">Air Freight (Medical Container)</strong>
                    </div>
                </div>

                <!-- Tracking Timeline -->
                <h3 style="font-size: 1.15rem; font-weight: 800; color: #0F172A; margin-bottom: 1.5rem; text-transform: uppercase; letter-spacing: 0.05em;">
                    Milestone Progress Timeline
                </h3>

                <div class="tracking-timeline">
                    <div class="timeline-step completed">
                        <div class="step-title">1. Shipment Created & Dispatch Order Issued</div>
                        <div class="step-time">Aug 12, 2026 - 08:30 AM</div>
                        <p style="font-size: 0.85rem; color: #64748B; margin-top: 0.25rem;">Waybill generated at JFK Logistics Hub. Package weighed and barcodes affixed.</p>
                    </div>

                    <div class="timeline-step completed">
                        <div class="step-title">2. Origin Terminal Pickup & Cargo Intake</div>
                        <div class="step-time">Aug 12, 2026 - 11:45 AM</div>
                        <p style="font-size: 0.85rem; color: #64748B; margin-top: 0.25rem;">Transferred from pickup truck to airport bonded warehouse.</p>
                    </div>

                    <div class="timeline-step completed">
                        <div class="step-title">3. Export Customs Audit & Clearance</div>
                        <div class="step-time">Aug 13, 2026 - 02:15 PM</div>
                        <p style="font-size: 0.85rem; color: #64748B; margin-top: 0.25rem;">Approved by US Customs & Border Protection for international air export.</p>
                    </div>

                    <div class="timeline-step current">
                        <div class="step-title">4. In Transit (Transatlantic Air Freight)</div>
                        <div class="step-time">Aug 14, 2026 - Active</div>
                        <p style="font-size: 0.85rem; color: #64748B; margin-top: 0.25rem;">Onboard Flight QX-702 at cruising altitude 35,000 ft over North Atlantic.</p>
                    </div>

                    <div class="timeline-step">
                        <div class="step-title">5. Destination Import Customs Inspection</div>
                        <div class="step-time">Scheduled: Aug 14 - 14:00 GMT</div>
                        <p style="font-size: 0.85rem; color: #64748B; margin-top: 0.25rem;">Pending arrival at Frankfurt International Airport (FRA) cargo terminal.</p>
                    </div>

                    <div class="timeline-step">
                        <div class="step-title">6. Out for Final Doorstep Delivery</div>
                        <div class="step-time">Scheduled: Aug 14 - 16:00 GMT</div>
                        <p style="font-size: 0.85rem; color: #64748B; margin-top: 0.25rem;">Handover to Qidex Express European Courier fleet for last-mile delivery.</p>
                    </div>

                    <div class="timeline-step">
                        <div class="step-title">7. Delivered & Recipient Signature</div>
                        <div class="step-time">Estimated: Aug 14 - 16:30 GMT</div>
                        <p style="font-size: 0.85rem; color: #64748B; margin-top: 0.25rem;">Final recipient signature capture and digital POD confirmation.</p>
                    </div>
                </div>

                <!-- Shipment Details & Overview Section -->
                <div style="margin-top: 3rem; padding-top: 2rem; border-top: 1px solid var(--border-color);">
                    <h3 style="font-size: 1.1rem; font-weight: 800; color: #0F172A; margin-bottom: 1rem; text-transform: uppercase;">
                        Cargo Specification & Technical Details
                    </h3>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; font-size: 0.9rem; color: #475569;">
                        <div>• Gross Weight: <strong>142.50 kg</strong></div>
                        <div>• Dimensions: <strong>120 x 80 x 100 cm</strong></div>
                        <div>• Chargeable Weight: <strong>160.00 kg</strong></div>
                        <div>• Pieces / Pallets: <strong>2 Pallets (Secured)</strong></div>
                        <div>• Temperature Range: <strong>2°C - 8°C Monitored</strong></div>
                        <div>• Insurance Value: <strong>$50,000 USD Covered</strong></div>
                    </div>
                </div>

            </div>

        </div>
    </div>

</main>

<?php
get_footer();
