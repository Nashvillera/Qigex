<?php
/**
 * The front page template file for Haivora Logistics theme
 *
 * @package Haivora_Logistics
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main id="primary" class="site-main">

    <!-- HERO SECTION -->
    <section class="hero-section" itemscope itemtype="https://schema.org/Service">
        <div class="container">
            <div class="hero-grid">
                
                <div class="hero-content">
                    <div class="hero-badge">
                        Intelligent Supply Chain
                    </div>

                    <h1 class="hero-headline" itemprop="name">
                        Global Shipping.<br>
                        <span>Smarter Logistics.</span>
                    </h1>

                    <p class="hero-subtext" itemprop="description">
                        Optimizing global commerce with precision-engineered shipping, real-time cargo visibility, express courier services, and dedicated logistics expertise across six continents.
                    </p>

                    <div class="hero-cta-group">
                        <a href="<?php echo esc_url(home_url('/track-shipment/')); ?>" class="btn btn-primary" style="padding: 1rem 2rem;">
                            TRACK SHIPMENT
                        </a>
                        <a href="<?php echo esc_url(home_url('/get-a-quote/')); ?>" class="btn btn-outline" style="padding: 1rem 2rem;">
                            GET A QUOTE
                        </a>
                    </div>
                </div>

                <div class="hero-visual">
                    <div style="background-color: #F1F5F9; border-radius: 8px; overflow: hidden; position: relative; height: 320px; box-shadow: var(--shadow-lg); border: 1px solid #E2E8F0;">
                        <div style="position: absolute; top: 1.25rem; right: 1.25rem; z-index: 2;">
                            <div style="background: rgba(255,255,255,0.95); backdrop-filter: blur(8px); padding: 0.85rem 1.25rem; border-radius: 4px; box-shadow: var(--shadow-md); border: 1px solid #FFFFFF;">
                                <div style="font-size: 0.65rem; text-transform: uppercase; color: #94A3B8; font-weight: 800; letter-spacing: 0.08em; margin-bottom: 0.25rem;">Live Aircraft Tracking</div>
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <div style="width: 8px; height: 8px; background-color: #10B981; border-radius: 50%;"></div>
                                    <span style="font-size: 0.85rem; font-weight: 800; color: #0F172A;">QX-4492 In Transit</span>
                                </div>
                            </div>
                        </div>
                        
                        <div style="height: 100%; width: 100%; opacity: 0.25; background-image: radial-gradient(#2563EB 1px, transparent 1px); background-size: 20px 20px;"></div>

                        <div style="position: absolute; bottom: 1.5rem; left: 1.5rem; color: #CBD5E1;">
                            <div style="font-size: 2.25rem; font-weight: 900; font-style: italic; opacity: 0.35;">QIDEX LOGISTICS</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- TRACKING WIDGET -->
    <section id="track" class="container" style="margin-top: -3rem; position: relative; z-index: 10; margin-bottom: 4rem;">
        <div style="background-color: #FFFFFF; border-radius: 6px; padding: 2rem; box-shadow: var(--shadow-lg); border: 1px solid var(--border-color);">
            
            <div style="margin-bottom: 1.25rem;">
                <h2 style="font-size: 1.15rem; font-weight: 800; color: #0F172A; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem;">
                    Shipment Tracking Portal
                </h2>
                <p style="font-size: 0.875rem; color: #64748B;">Enter your tracking reference for real-time status and telemetry details.</p>
            </div>

            <form id="home-tracking-form" action="<?php echo esc_url(home_url('/track-shipment/')); ?>" method="get">
                <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                    <input type="text" name="tracking_id" id="home-tracking-input" class="tracking-input" placeholder="Enter tracking number (e.g. QX-8829-US)..." value="QX-8829-US" required style="flex-grow: 1; min-width: 260px; padding: 0.85rem 1.25rem; border: 2px solid #E2E8F0; border-radius: 4px; font-weight: 600; font-size: 0.95rem;">
                    <button type="submit" class="btn btn-primary" style="padding: 0.85rem 1.75rem;">
                        TRACK SHIPMENT
                    </button>
                </div>
            </form>

            <div style="margin-top: 1rem; display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; font-size: 0.85rem; color: #64748B;">
                <span>Try Demo Numbers:</span>
                <button type="button" class="demo-pill" data-code="QX-8829-US">QX-8829-US</button>
                <button type="button" class="demo-pill" data-code="QX-9912-DE">QX-9912-DE</button>
                <button type="button" class="demo-pill" data-code="QX-3301-CN">QX-3301-CN</button>
            </div>

            <!-- Prepared Result Display Area -->
            <div id="tracking-results-box" class="tracking-results-box" style="margin-top: 1.5rem; padding: 1.5rem; background-color: #F8FAFC; border-radius: 4px; border: 1px solid #E2E8F0;">
                
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #E2E8F0; padding-bottom: 1rem; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 1rem;">
                    <div>
                        <span style="font-size: 0.75rem; font-weight: 800; text-transform: uppercase; color: #94A3B8; letter-spacing: 0.05em; display: block;">TRACKING NUMBER</span>
                        <strong id="result-shipment-id" style="font-size: 1.25rem; color: #0F172A; font-family: var(--font-mono);">QX-8829-US</strong>
                    </div>
                    <div>
                        <span style="font-size: 0.75rem; font-weight: 800; text-transform: uppercase; color: #94A3B8; letter-spacing: 0.05em; display: block;">STATUS</span>
                        <span id="result-status-badge" class="status-badge in-transit" style="padding: 0.3rem 0.75rem; border-radius: 9999px; font-weight: 800; font-size: 0.8rem; background-color: rgba(37, 99, 235, 0.12); color: #2563EB;">IN TRANSIT</span>
                    </div>
                    <a href="<?php echo esc_url(home_url('/track-shipment/?id=QX-8829-US')); ?>" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.8rem;">
                        Full Overview &rarr;
                    </a>
                </div>

                <!-- Comprehensive Prepared Metadata Grid -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; background-color: #FFFFFF; padding: 1rem; border-radius: 4px; border: 1px solid #E2E8F0;">
                    <div>
                        <span style="font-size: 0.75rem; color: #64748B; display: block; font-weight: 600;">Origin</span>
                        <strong id="meta-origin" style="font-size: 0.9rem; color: #0F172A;">JFK, New York, USA</strong>
                    </div>
                    <div>
                        <span style="font-size: 0.75rem; color: #64748B; display: block; font-weight: 600;">Destination</span>
                        <strong id="meta-destination" style="font-size: 0.9rem; color: #0F172A;">FRA, Frankfurt, Germany</strong>
                    </div>
                    <div>
                        <span style="font-size: 0.75rem; color: #64748B; display: block; font-weight: 600;">Current Location</span>
                        <strong id="meta-location" style="font-size: 0.9rem; color: #2563EB;">Flight QX-702 (Atlantic)</strong>
                    </div>
                    <div>
                        <span style="font-size: 0.75rem; color: #64748B; display: block; font-weight: 600;">Estimated Delivery</span>
                        <strong id="meta-delivery" style="font-size: 0.9rem; color: #10B981;">Aug 14, 2026 - 16:30 GMT</strong>
                    </div>
                    <div>
                        <span style="font-size: 0.75rem; color: #64748B; display: block; font-weight: 600;">Shipment Date</span>
                        <strong id="meta-shipdate" style="font-size: 0.85rem; color: #0F172A;">Aug 12, 2026</strong>
                    </div>
                    <div>
                        <span style="font-size: 0.75rem; color: #64748B; display: block; font-weight: 600;">Last Update</span>
                        <strong id="meta-lastupdate" style="font-size: 0.85rem; color: #0F172A;">10 mins ago</strong>
                    </div>
                    <div>
                        <span style="font-size: 0.75rem; color: #64748B; display: block; font-weight: 600;">Carrier</span>
                        <strong id="meta-carrier" style="font-size: 0.85rem; color: #0F172A;">Qidex Air Cargo</strong>
                    </div>
                    <div>
                        <span style="font-size: 0.75rem; color: #64748B; display: block; font-weight: 600;">Package Type</span>
                        <strong id="meta-packagetype" style="font-size: 0.85rem; color: #0F172A;">Express Container</strong>
                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- SERVICES (8 REQUIRED CARDS) -->
    <section id="services" class="section-padding" style="background-color: #F8FAFC; border-top: 1px solid var(--border-color);">
        <div class="container">
            
            <div class="section-header">
                <span class="section-subtitle">SERVICES</span>
                <h2 class="section-title">End-to-End Freight & Logistics</h2>
            </div>

            <div class="services-grid">
                
                <div class="service-card">
                    <div class="service-icon">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                    </div>
                    <h3 class="service-title">Express Delivery</h3>
                    <p class="service-description">Priority courier for time-sensitive parcels with guaranteed time-slot delivery.</p>
                    <a href="<?php echo esc_url(home_url('/services/')); ?>" class="service-link">Learn More CTA &rarr;</a>
                </div>

                <div class="service-card">
                    <div class="service-icon">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3.5c-.5-.5-2.5 0-4 1.5L13.5 8.5 5.3 6.7c-.8-.2-1.6.1-2.1.8l-.4.6 5.3 3.8-3.1 3.1-2.2-.6-1 .5 2.1 2.1 2.1 2.1.5-1-.6-2.2 3.1-3.1 3.8 5.3.6-.4c.7-.5 1-1.3.8-2.1z"></path></svg>
                    </div>
                    <h3 class="service-title">Air Freight</h3>
                    <p class="service-description">Fastest international transit for high-value commercial shipments and cargo.</p>
                    <a href="<?php echo esc_url(home_url('/services/')); ?>" class="service-link">Learn More CTA &rarr;</a>
                </div>

                <div class="service-card">
                    <div class="service-icon">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 20a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8l-7-5-5 3.5L3 3v17z"></path><path d="M2 12h20"></path></svg>
                    </div>
                    <h3 class="service-title">Sea Freight</h3>
                    <p class="service-description">FCL and LCL ocean cargo solutions connecting major worldwide maritime trade routes.</p>
                    <a href="<?php echo esc_url(home_url('/services/')); ?>" class="service-link">Learn More CTA &rarr;</a>
                </div>

                <div class="service-card">
                    <div class="service-icon">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                    </div>
                    <h3 class="service-title">Road Freight</h3>
                    <p class="service-description">Cross-border overland trucking, FTL, LTL, and regional distribution networks.</p>
                    <a href="<?php echo esc_url(home_url('/services/')); ?>" class="service-link">Learn More CTA &rarr;</a>
                </div>

                <div class="service-card">
                    <div class="service-icon">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                    </div>
                    <h3 class="service-title">Warehousing</h3>
                    <p class="service-description">Climate-controlled storage, pick-and-pack fulfillment, and inventory analytics.</p>
                    <a href="<?php echo esc_url(home_url('/services/')); ?>" class="service-link">Learn More CTA &rarr;</a>
                </div>

                <div class="service-card">
                    <div class="service-icon">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                    </div>
                    <h3 class="service-title">Customs Clearance</h3>
                    <p class="service-description">Licensed brokerage, duty optimization, tariff compliance, and documentation clearance.</p>
                    <a href="<?php echo esc_url(home_url('/services/')); ?>" class="service-link">Learn More CTA &rarr;</a>
                </div>

                <div class="service-card">
                    <div class="service-icon">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                    </div>
                    <h3 class="service-title">Last-Mile Delivery</h3>
                    <p class="service-description">Doorstep delivery ensuring accurate recipient handover and electronic proof of delivery.</p>
                    <a href="<?php echo esc_url(home_url('/services/')); ?>" class="service-link">Learn More CTA &rarr;</a>
                </div>

                <div class="service-card">
                    <div class="service-icon">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                    </div>
                    <h3 class="service-title">International Shipping</h3>
                    <p class="service-description">Worldwide multi-modal logistics connecting North America, Europe, Asia, and global markets.</p>
                    <a href="<?php echo esc_url(home_url('/services/')); ?>" class="service-link">Learn More CTA &rarr;</a>
                </div>

            </div>
        </div>
    </section>

    <!-- WHY CHOOSE US (6 REQUIRED ITEMS) -->
    <section id="why-us" class="section-padding why-section">
        <div class="container">
            
            <div class="section-header" style="color: #FFFFFF;">
                <span class="section-subtitle">THE QIDEX ADVANTAGE</span>
                <h2 class="section-title" style="color: #FFFFFF;">Why Choose Us</h2>
            </div>

            <div class="why-grid">
                
                <div class="feature-box">
                    <h3 class="feature-title">1. Real-Time Tracking</h3>
                    <p class="feature-desc">Continuous GPS and sensor telemetry across air, sea, and highway trade routes.</p>
                </div>

                <div class="feature-box">
                    <h3 class="feature-title">2. Secure Handling</h3>
                    <p class="feature-desc">Tamper-evident seals, chain-of-custody tracking, and bonded warehouse security.</p>
                </div>

                <div class="feature-box">
                    <h3 class="feature-title">3. Fast Delivery</h3>
                    <p class="feature-desc">Optimized carrier routing and expedited customs channels for shortened lead times.</p>
                </div>

                <div class="feature-box">
                    <h3 class="feature-title">4. Global Network</h3>
                    <p class="feature-desc">Direct presence across 120+ countries with regional hubs on six continents.</p>
                </div>

                <div class="feature-box">
                    <h3 class="feature-title">5. Professional Support</h3>
                    <p class="feature-desc">24/7 dedicated human logistics account managers on standby to assist.</p>
                </div>

                <div class="feature-box">
                    <h3 class="feature-title">6. Transparent Pricing</h3>
                    <p class="feature-desc">All-inclusive freight rates with zero hidden surcharges or unexpected terminal fees.</p>
                </div>

            </div>
        </div>
    </section>

    <!-- SHIPMENT PROCESS (7 REQUIRED STEPS) -->
    <section class="section-padding" style="background-color: #FFFFFF;">
        <div class="container">
            
            <div class="section-header">
                <span class="section-subtitle">PROCESS TIMELINE</span>
                <h2 class="section-title">7-Step Shipment Journey</h2>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(130px, 1fr)); gap: 1rem; text-align: center;">
                
                <div style="background-color: #F8FAFC; padding: 1.25rem 0.75rem; border-radius: 4px; border: 1px solid #E2E8F0;">
                    <div style="width: 32px; height: 32px; background-color: #2563EB; color: #FFFFFF; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; margin: 0 auto 0.5rem; font-size: 0.85rem;">1</div>
                    <h3 style="font-size: 0.85rem; font-weight: 800; color: #0F172A;">Book Shipment</h3>
                </div>

                <div style="background-color: #F8FAFC; padding: 1.25rem 0.75rem; border-radius: 4px; border: 1px solid #E2E8F0;">
                    <div style="width: 32px; height: 32px; background-color: #2563EB; color: #FFFFFF; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; margin: 0 auto 0.5rem; font-size: 0.85rem;">2</div>
                    <h3 style="font-size: 0.85rem; font-weight: 800; color: #0F172A;">Pickup</h3>
                </div>

                <div style="background-color: #F8FAFC; padding: 1.25rem 0.75rem; border-radius: 4px; border: 1px solid #E2E8F0;">
                    <div style="width: 32px; height: 32px; background-color: #2563EB; color: #FFFFFF; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; margin: 0 auto 0.5rem; font-size: 0.85rem;">3</div>
                    <h3 style="font-size: 0.85rem; font-weight: 800; color: #0F172A;">Processing</h3>
                </div>

                <div style="background-color: #F8FAFC; padding: 1.25rem 0.75rem; border-radius: 4px; border: 1px solid #E2E8F0;">
                    <div style="width: 32px; height: 32px; background-color: #2563EB; color: #FFFFFF; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; margin: 0 auto 0.5rem; font-size: 0.85rem;">4</div>
                    <h3 style="font-size: 0.85rem; font-weight: 800; color: #0F172A;">In Transit</h3>
                </div>

                <div style="background-color: #F8FAFC; padding: 1.25rem 0.75rem; border-radius: 4px; border: 1px solid #E2E8F0;">
                    <div style="width: 32px; height: 32px; background-color: #2563EB; color: #FFFFFF; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; margin: 0 auto 0.5rem; font-size: 0.85rem;">5</div>
                    <h3 style="font-size: 0.85rem; font-weight: 800; color: #0F172A;">Customs</h3>
                </div>

                <div style="background-color: #F8FAFC; padding: 1.25rem 0.75rem; border-radius: 4px; border: 1px solid #E2E8F0;">
                    <div style="width: 32px; height: 32px; background-color: #2563EB; color: #FFFFFF; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; margin: 0 auto 0.5rem; font-size: 0.85rem;">6</div>
                    <h3 style="font-size: 0.85rem; font-weight: 800; color: #0F172A;">Out for Delivery</h3>
                </div>

                <div style="background-color: #F8FAFC; padding: 1.25rem 0.75rem; border-radius: 4px; border: 1px solid #E2E8F0;">
                    <div style="width: 32px; height: 32px; background-color: #10B981; color: #FFFFFF; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; margin: 0 auto 0.5rem; font-size: 0.85rem;">7</div>
                    <h3 style="font-size: 0.85rem; font-weight: 800; color: #0F172A;">Delivered</h3>
                </div>

            </div>
        </div>
    </section>

    <!-- STATISTICS (REQUIRED Figures Marked as Demonstration) -->
    <section class="stats-section">
        <div class="container">
            <div style="margin-bottom: 2rem; text-align: center;">
                <span style="font-size: 0.75rem; background: rgba(255,255,255,0.15); padding: 0.2rem 0.75rem; border-radius: 9999px; text-transform: uppercase; font-weight: 700; letter-spacing: 0.08em; color: #38BDF8;">Demonstration Performance Figures</span>
            </div>
            <div class="stats-grid">
                
                <div class="stat-box">
                    <div class="stat-number">50K+</div>
                    <div class="stat-label">Shipments Delivered</div>
                </div>

                <div class="stat-box">
                    <div class="stat-number">120+</div>
                    <div class="stat-label">Countries Served</div>
                </div>

                <div class="stat-box">
                    <div class="stat-number">98%</div>
                    <div class="stat-label">On-Time Delivery</div>
                </div>

                <div class="stat-box">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Customer Support</div>
                </div>

            </div>
        </div>
    </section>

    <!-- TESTIMONIALS (Marked as Demonstration) -->
    <section class="section-padding" style="background-color: #F8FAFC;">
        <div class="container">
            
            <div class="section-header">
                <span class="section-subtitle">DEMONSTRATION TESTIMONIALS</span>
                <h2 class="section-title">What Our Global Partners Say</h2>
            </div>

            <div class="testimonials-grid">
                
                <div class="testimonial-card">
                    <p class="testimonial-quote">
                        "Qidex Express transformed our transatlantic supply chain. Their tracking portal and express customs clearance saved us hundreds of hours during peak season."
                    </p>
                    <div class="author-info">
                        <div class="author-name">Marcus Sterling</div>
                        <div class="author-role">VP Supply Chain, TechGlobal Inc. [Demo Review]</div>
                    </div>
                </div>

                <div class="testimonial-card">
                    <p class="testimonial-quote">
                        "The ocean freight team handled our machinery transport from Hamburg to Singapore flawlessly with complete transparency."
                    </p>
                    <div class="author-info">
                        <div class="author-name">Elena Lindqvist</div>
                        <div class="author-role">Logistics Director, EuroCraft GmbH [Demo Review]</div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="section-padding">
        <div class="container">
            <div class="cta-banner">
                <h2 class="cta-title">Ready to Streamline Your Global Shipping?</h2>
                <p class="cta-desc">Get an instant competitive rate quote or contact our 24/7 logistics team today.</p>
                <div style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
                    <a href="<?php echo esc_url(home_url('/get-a-quote/')); ?>" class="btn btn-dark" style="padding: 0.85rem 2rem;">
                        GET A QUOTE
                    </a>
                    <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="btn" style="background: #FFFFFF; color: #0F172A; padding: 0.85rem 2rem; font-weight: 700;">
                        CONTACT US
                    </a>
                </div>
            </div>
        </div>
    </section>

</main>

<?php
get_footer();
