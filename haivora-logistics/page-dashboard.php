<?php
/**
 * Template Name: Customer Dashboard
 * Page Template for /dashboard/
 *
 * @package Haivora_Logistics
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main id="primary" class="site-main">

    <div class="dashboard-wrapper" style="background-color: #F8FAFC; min-height: 90vh; padding: 2rem 0;">
        <div class="container">
            
            <!-- Dashboard Top Header Bar -->
            <div style="background: #FFFFFF; border-radius: 8px; border: 1px solid #E2E8F0; padding: 1.25rem 1.75rem; margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; box-shadow: var(--shadow-sm);">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div id="user-avatar" style="width: 46px; height: 46px; background: #0F172A; color: #FFFFFF; border-radius: 50%; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; justify-content: center;">
                        CU
                    </div>
                    <div>
                        <h1 id="user-welcome-title" style="font-size: 1.25rem; font-weight: 800; color: #0F172A; margin: 0;">
                            Welcome back, <span id="user-display-name">Valued Customer</span>
                        </h1>
                        <p style="font-size: 0.8rem; color: #64748B; margin: 0.1rem 0 0 0;">
                            Commercial Shipper Account • <span id="user-email-badge">client@company.com</span>
                        </p>
                    </div>
                </div>

                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <a href="/shipment-admin" class="btn" style="background: #FFF7ED; color: #C2410C; border: 1px solid #FFEDD5; font-size: 0.8rem; padding: 0.5rem 0.9rem; font-weight: 700; border-radius: 6px;">
                        📦 Admin Portal
                    </a>
                    <button id="btn-dashboard-logout" class="btn" style="background: #F1F5F9; color: #334155; border: 1px solid #CBD5E1; font-size: 0.8rem; padding: 0.5rem 0.9rem; font-weight: 700; border-radius: 6px;">
                        🚪 Logout
                    </button>
                </div>
            </div>

            <!-- Dashboard Main Grid Layout -->
            <div style="display: grid; grid-template-columns: 240px 1fr; gap: 1.5rem;" class="dashboard-grid-container">
                
                <!-- Sidebar Navigation Menu -->
                <aside style="background: #FFFFFF; border-radius: 8px; border: 1px solid #E2E8F0; padding: 1.25rem 0.75rem; height: fit-content; box-shadow: var(--shadow-sm);">
                    <div style="font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: #94A3B8; padding: 0 0.75rem 0.5rem 0.75rem; border-bottom: 1px solid #F1F5F9; margin-bottom: 0.5rem;">
                        Portal Menu
                    </div>
                    <nav class="dash-nav-list" style="display: flex; flex-direction: column; gap: 0.25rem;">
                        <button class="dash-tab-btn active" data-tab="overview">
                            <span>📊 Overview</span>
                        </button>
                        <button class="dash-tab-btn" data-tab="shipments">
                            <span>📦 My Shipments</span>
                            <span id="tab-shipment-count" style="margin-left: auto; background: #EFF6FF; color: #2563EB; font-size: 0.7rem; padding: 2px 6px; border-radius: 999px; font-weight: 800;">3</span>
                        </button>
                        <button class="dash-tab-btn" data-tab="track">
                            <span>🔍 Track Shipment</span>
                        </button>
                        <button class="dash-tab-btn" data-tab="history">
                            <span>📜 Shipment History</span>
                        </button>
                        <button class="dash-tab-btn" data-tab="profile">
                            <span>👤 Account Profile</span>
                        </button>
                        <button class="dash-tab-btn" data-tab="notifications">
                            <span>🔔 Notifications</span>
                            <span id="tab-notif-dot" style="width: 8px; height: 8px; background: #EF4444; border-radius: 50%; margin-left: auto;"></span>
                        </button>
                        <button class="dash-tab-btn" data-tab="support">
                            <span>💬 Customer Support</span>
                        </button>
                    </nav>
                </aside>

                <!-- Dashboard Content Area -->
                <section style="background: #FFFFFF; border-radius: 8px; border: 1px solid #E2E8F0; padding: 1.75rem; box-shadow: var(--shadow-sm); min-height: 500px;">
                    
                    <!-- Alert Message Bar -->
                    <div id="dash-alert-bar" style="display: none; padding: 0.85rem 1rem; border-radius: 6px; margin-bottom: 1.25rem; font-size: 0.85rem; font-weight: 600;"></div>

                    <!-- VIEW 1: OVERVIEW -->
                    <div id="tab-view-overview" class="dash-view-pane active">
                        <h2 style="font-size: 1.25rem; font-weight: 800; color: #0F172A; margin-bottom: 1.25rem;">
                            Dashboard Overview
                        </h2>

                        <!-- Statistics Grid Cards -->
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 1.75rem;">
                            <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-left: 4px solid #2563EB; border-radius: 6px; padding: 1.25rem;">
                                <div style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: #64748B; margin-bottom: 0.25rem;">Active Shipments</div>
                                <div id="stat-active" style="font-family: 'Outfit', sans-serif; font-size: 1.8rem; font-weight: 900; color: #0F172A;">2</div>
                            </div>
                            <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-left: 4px solid #10B981; border-radius: 6px; padding: 1.25rem;">
                                <div style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: #64748B; margin-bottom: 0.25rem;">Delivered</div>
                                <div id="stat-delivered" style="font-family: 'Outfit', sans-serif; font-size: 1.8rem; font-weight: 900; color: #0F172A;">1</div>
                            </div>
                            <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-left: 4px solid #F59E0B; border-radius: 6px; padding: 1.25rem;">
                                <div style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: #64748B; margin-bottom: 0.25rem;">Customs / Pending</div>
                                <div id="stat-pending" style="font-family: 'Outfit', sans-serif; font-size: 1.8rem; font-weight: 900; color: #0F172A;">1</div>
                            </div>
                            <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-left: 4px solid #EF4444; border-radius: 6px; padding: 1.25rem;">
                                <div style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: #64748B; margin-bottom: 0.25rem;">Delayed / Hold</div>
                                <div id="stat-delayed" style="font-family: 'Outfit', sans-serif; font-size: 1.8rem; font-weight: 900; color: #0F172A;">0</div>
                            </div>
                        </div>

                        <!-- Active Shipments Summary Table -->
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                            <h3 style="font-size: 1rem; font-weight: 800; color: #0F172A; margin: 0;">Recent Active Shipments</h3>
                            <button class="btn-link-tab" data-target="shipments" style="font-size: 0.8rem; color: #2563EB; font-weight: 700; background: none; border: none; cursor: pointer;">View All Shipments &rarr;</button>
                        </div>

                        <div style="overflow-x: auto; border: 1px solid #E2E8F0; border-radius: 6px; margin-bottom: 1.75rem;">
                            <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
                                <thead>
                                    <tr style="background: #F8FAFC; text-align: left; border-bottom: 1px solid #E2E8F0;">
                                        <th style="padding: 0.75rem 1rem; font-weight: 800; color: #475569;">Tracking #</th>
                                        <th style="padding: 0.75rem 1rem; font-weight: 800; color: #475569;">Route</th>
                                        <th style="padding: 0.75rem 1rem; font-weight: 800; color: #475569;">Status</th>
                                        <th style="padding: 0.75rem 1rem; font-weight: 800; color: #475569;">Est. Delivery</th>
                                        <th style="padding: 0.75rem 1rem; font-weight: 800; color: #475569;">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="overview-shipments-body">
                                    <!-- Populated dynamically via JS -->
                                </tbody>
                            </table>
                        </div>

                        <!-- Quick Actions Grid -->
                        <div style="background: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 6px; padding: 1.25rem;">
                            <h4 style="font-size: 0.9rem; font-weight: 800; color: #1E40AF; margin: 0 0 0.5rem 0;">⚡ Quick Dispatch Services</h4>
                            <p style="font-size: 0.8rem; color: #3B82F6; margin-bottom: 1rem;">Need to schedule a pick-up or amend waybill details? Contact your assigned dispatch manager.</p>
                            <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                                <a href="/get-a-quote/" class="btn btn-primary" style="font-size: 0.8rem; padding: 0.5rem 1rem;">Request Instant Quote</a>
                                <button class="btn-link-tab btn" data-target="support" style="background: #FFFFFF; color: #1E40AF; border: 1px solid #BFDBFE; font-size: 0.8rem; padding: 0.5rem 1rem; font-weight: 700;">Open Support Ticket</button>
                            </div>
                        </div>
                    </div>

                    <!-- VIEW 2: MY SHIPMENTS -->
                    <div id="tab-view-shipments" class="dash-view-pane">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 0.5rem;">
                            <h2 style="font-size: 1.25rem; font-weight: 800; color: #0F172A; margin: 0;">
                                My Consignments & Waybills
                            </h2>
                            <input type="text" id="cust-shipment-search" placeholder="Search by tracking #, origin, destination..." style="padding: 0.5rem 0.8rem; border: 1px solid #CBD5E1; border-radius: 6px; font-size: 0.85rem; width: 260px;">
                        </div>

                        <div style="overflow-x: auto; border: 1px solid #E2E8F0; border-radius: 6px;">
                            <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
                                <thead>
                                    <tr style="background: #F8FAFC; text-align: left; border-bottom: 1px solid #E2E8F0;">
                                        <th style="padding: 0.85rem 1rem; font-weight: 800; color: #475569;">Tracking #</th>
                                        <th style="padding: 0.85rem 1rem; font-weight: 800; color: #475569;">Origin ➔ Destination</th>
                                        <th style="padding: 0.85rem 1rem; font-weight: 800; color: #475569;">Status</th>
                                        <th style="padding: 0.85rem 1rem; font-weight: 800; color: #475569;">Carrier & Type</th>
                                        <th style="padding: 0.85rem 1rem; font-weight: 800; color: #475569;">Est. Delivery</th>
                                        <th style="padding: 0.85rem 1rem; font-weight: 800; color: #475569;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="my-shipments-table-body">
                                    <!-- Dynamic rows -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- VIEW 3: TRACK SHIPMENT -->
                    <div id="tab-view-track" class="dash-view-pane">
                        <h2 style="font-size: 1.25rem; font-weight: 800; color: #0F172A; margin-bottom: 1rem;">
                            Live Tracking Lookup
                        </h2>
                        <div style="display: flex; gap: 0.75rem; margin-bottom: 1.5rem;">
                            <input type="text" id="dash-track-input" placeholder="Enter tracking number (e.g. QX-8829-US)" style="flex: 1; padding: 0.75rem 1rem; border: 1px solid #CBD5E1; border-radius: 6px; font-size: 0.9rem; font-family: monospace; font-weight: bold; text-transform: uppercase;">
                            <button id="btn-dash-track" class="btn btn-primary" style="padding: 0.75rem 1.5rem; font-size: 0.85rem;">TRACK NOW</button>
                        </div>
                        <div id="dash-track-result" style="display: none; background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 6px; padding: 1.5rem;">
                            <!-- Track output -->
                        </div>
                    </div>

                    <!-- VIEW 4: SHIPMENT HISTORY -->
                    <div id="tab-view-history" class="dash-view-pane">
                        <h2 style="font-size: 1.25rem; font-weight: 800; color: #0F172A; margin-bottom: 1.25rem;">
                            Completed & Delivered Archive
                        </h2>
                        <div style="overflow-x: auto; border: 1px solid #E2E8F0; border-radius: 6px;">
                            <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
                                <thead>
                                    <tr style="background: #F8FAFC; text-align: left; border-bottom: 1px solid #E2E8F0;">
                                        <th style="padding: 0.85rem 1rem; font-weight: 800; color: #475569;">Tracking #</th>
                                        <th style="padding: 0.85rem 1rem; font-weight: 800; color: #475569;">Route</th>
                                        <th style="padding: 0.85rem 1rem; font-weight: 800; color: #475569;">Delivered Date</th>
                                        <th style="padding: 0.85rem 1rem; font-weight: 800; color: #475569;">Consignee POD</th>
                                        <th style="padding: 0.85rem 1rem; font-weight: 800; color: #475569;">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="history-shipments-table-body">
                                    <!-- Completed shipments -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- VIEW 5: ACCOUNT PROFILE -->
                    <div id="tab-view-profile" class="dash-view-pane">
                        <h2 style="font-size: 1.25rem; font-weight: 800; color: #0F172A; margin-bottom: 1.25rem;">
                            Account Profile & Settings
                        </h2>

                        <form id="profile-update-form" style="max-width: 600px;">
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem;">
                                <div>
                                    <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #0F172A; margin-bottom: 0.35rem;">First Name</label>
                                    <input type="text" id="prof-firstname" required style="width: 100%; padding: 0.75rem; border: 1px solid #CBD5E1; border-radius: 6px; font-size: 0.85rem;">
                                </div>
                                <div>
                                    <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #0F172A; margin-bottom: 0.35rem;">Last Name</label>
                                    <input type="text" id="prof-lastname" required style="width: 100%; padding: 0.75rem; border: 1px solid #CBD5E1; border-radius: 6px; font-size: 0.85rem;">
                                </div>
                            </div>

                            <div style="margin-bottom: 1.25rem;">
                                <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #0F172A; margin-bottom: 0.35rem;">Email Address (Read-only)</label>
                                <input type="email" id="prof-email" readonly style="width: 100%; padding: 0.75rem; border: 1px solid #E2E8F0; background: #F8FAFC; border-radius: 6px; font-size: 0.85rem; color: #64748B;">
                            </div>

                            <div style="margin-bottom: 1.25rem;">
                                <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #0F172A; margin-bottom: 0.35rem;">Phone Number</label>
                                <input type="tel" id="prof-phone" required style="width: 100%; padding: 0.75rem; border: 1px solid #CBD5E1; border-radius: 6px; font-size: 0.85rem;">
                            </div>

                            <div style="margin-bottom: 1.25rem;">
                                <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #0F172A; margin-bottom: 0.35rem;">Company / Organization</label>
                                <input type="text" id="prof-company" style="width: 100%; padding: 0.75rem; border: 1px solid #CBD5E1; border-radius: 6px; font-size: 0.85rem;">
                            </div>

                            <div style="margin-bottom: 1.5rem;">
                                <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #0F172A; margin-bottom: 0.35rem;">Account Role Permission</label>
                                <input type="text" value="Customer / Commercial Shipper (Restricted)" disabled style="width: 100%; padding: 0.75rem; border: 1px solid #E2E8F0; background: #F8FAFC; border-radius: 6px; font-size: 0.85rem; color: #64748B;">
                                <p style="font-size: 0.75rem; color: #94A3B8; margin-top: 0.25rem;">🔒 Customer roles cannot alter administrative privileges.</p>
                            </div>

                            <div style="border-top: 1px solid #E2E8F0; padding-top: 1.25rem; margin-top: 1.25rem;">
                                <h3 style="font-size: 0.95rem; font-weight: 800; color: #0F172A; margin-bottom: 1rem;">Change Password</h3>
                                
                                <div style="margin-bottom: 1rem;">
                                    <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #0F172A; margin-bottom: 0.35rem;">Current Password</label>
                                    <input type="password" id="prof-curr-pass" placeholder="••••••••••••" style="width: 100%; padding: 0.75rem; border: 1px solid #CBD5E1; border-radius: 6px; font-size: 0.85rem;">
                                </div>
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem;">
                                    <div>
                                        <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #0F172A; margin-bottom: 0.35rem;">New Password</label>
                                        <input type="password" id="prof-new-pass" placeholder="Min 6 chars" style="width: 100%; padding: 0.75rem; border: 1px solid #CBD5E1; border-radius: 6px; font-size: 0.85rem;">
                                    </div>
                                    <div>
                                        <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #0F172A; margin-bottom: 0.35rem;">Confirm New Password</label>
                                        <input type="password" id="prof-confirm-pass" placeholder="Re-enter new pass" style="width: 100%; padding: 0.75rem; border: 1px solid #CBD5E1; border-radius: 6px; font-size: 0.85rem;">
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary" style="padding: 0.75rem 1.5rem; font-size: 0.85rem;">SAVE PROFILE CHANGES</button>
                        </form>
                    </div>

                    <!-- VIEW 6: NOTIFICATIONS -->
                    <div id="tab-view-notifications" class="dash-view-pane">
                        <h2 style="font-size: 1.25rem; font-weight: 800; color: #0F172A; margin-bottom: 1.25rem;">
                            Shipment Notification Preferences & Feed
                        </h2>

                        <!-- Channel Preferences Card -->
                        <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 6px; padding: 1.25rem; margin-bottom: 1.5rem;">
                            <h3 style="font-size: 0.95rem; font-weight: 800; color: #0F172A; margin-bottom: 0.75rem;">Dispatched Alerts Channels</h3>
                            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                                <label style="display: flex; align-items: center; gap: 0.75rem; font-size: 0.85rem; font-weight: 600; cursor: pointer;">
                                    <input type="checkbox" id="notif-pref-email" checked style="width: 18px; height: 18px;">
                                    <span>📧 Email Notifications (Waybill PDF & Status Milestones)</span>
                                </label>
                                <label style="display: flex; align-items: center; gap: 0.75rem; font-size: 0.85rem; font-weight: 600; cursor: pointer;">
                                    <input type="checkbox" id="notif-pref-whatsapp" checked style="width: 18px; height: 18px;">
                                    <span>💬 WhatsApp Mobile Instant Alerts (Live Transit Coordinates)</span>
                                </label>
                                <label style="display: flex; align-items: center; gap: 0.75rem; font-size: 0.85rem; font-weight: 600; cursor: pointer;">
                                    <input type="checkbox" id="notif-pref-sms" style="width: 18px; height: 18px;">
                                    <span>📱 SMS Text Alerts (Terminal Delivery & Clearance Updates)</span>
                                </label>
                            </div>
                            <button id="btn-save-notif-prefs" class="btn btn-primary" style="margin-top: 1rem; padding: 0.5rem 1rem; font-size: 0.8rem;">Save Preferences</button>
                        </div>

                        <!-- Notification History Feed -->
                        <h3 style="font-size: 1rem; font-weight: 800; color: #0F172A; margin-bottom: 0.75rem;">Recent Dispatched Alerts</h3>
                        <div id="notifications-feed-list" style="display: flex; flex-direction: column; gap: 0.75rem;">
                            <!-- Dynamic items -->
                        </div>
                    </div>

                    <!-- VIEW 7: SUPPORT -->
                    <div id="tab-view-support" class="dash-view-pane">
                        <h2 style="font-size: 1.25rem; font-weight: 800; color: #0F172A; margin-bottom: 1.25rem;">
                            Customer Support & Waybill Assistance
                        </h2>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;" class="support-grid">
                            <form id="support-ticket-form">
                                <div style="margin-bottom: 1rem;">
                                    <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #0F172A; margin-bottom: 0.35rem;">Subject / Inquiry Type</label>
                                    <select id="support-subject" style="width: 100%; padding: 0.75rem; border: 1px solid #CBD5E1; border-radius: 6px; font-size: 0.85rem;">
                                        <option value="tracking">Shipment Location Query</option>
                                        <option value="customs">Customs Hold & Clearance Document</option>
                                        <option value="address">Delivery Address Amendment</option>
                                        <option value="claim">Cargo Loss / Damage Inquiry</option>
                                        <option value="other">General Commercial Inquiry</option>
                                    </select>
                                </div>
                                <div style="margin-bottom: 1rem;">
                                    <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #0F172A; margin-bottom: 0.35rem;">Associated Waybill Tracking #</label>
                                    <input type="text" id="support-tracking" placeholder="e.g. QX-8829-US" style="width: 100%; padding: 0.75rem; border: 1px solid #CBD5E1; border-radius: 6px; font-size: 0.85rem;">
                                </div>
                                <div style="margin-bottom: 1.25rem;">
                                    <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #0F172A; margin-bottom: 0.35rem;">Detailed Message</label>
                                    <textarea id="support-message" rows="4" required placeholder="Describe your request..." style="width: 100%; padding: 0.75rem; border: 1px solid #CBD5E1; border-radius: 6px; font-size: 0.85rem;"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary" style="padding: 0.75rem 1.5rem; font-size: 0.85rem;">Submit Ticket</button>
                            </form>

                            <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 6px; padding: 1.25rem;">
                                <h3 style="font-size: 0.95rem; font-weight: 800; color: #0F172A; margin-bottom: 0.75rem;">24/7 Priority Hotline</h3>
                                <p style="font-size: 0.85rem; color: #64748B; margin-bottom: 1rem;">Commercial accounts enjoy dedicated phone support for high-priority air & ocean freight shipments.</p>
                                <div style="font-size: 1.1rem; font-weight: 800; color: #2563EB; margin-bottom: 0.5rem;">📞 +1 (800) QIDEX-LOG</div>
                                <div style="font-size: 0.85rem; color: #475569; margin-bottom: 1rem;">✉️ dispatch@qidexexpress.com</div>
                                <div style="border-top: 1px solid #E2E8F0; padding-top: 0.75rem; font-size: 0.75rem; color: #94A3B8;">
                                    Operating Hubs: JFK Freight Hub (US) • Frankfurt Airport (DE) • Changi Cargo Terminal (SG)
                                </div>
                            </div>
                        </div>
                    </div>

                </section>
            </div>

        </div>
    </div>

    <!-- Shipment Detail Modal -->
    <div id="shipment-detail-modal" class="modal-overlay" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
        <div style="background: #FFF; border-radius: 8px; width: 100%; max-width: 800px; max-height: 90vh; overflow-y: auto; padding: 25px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.2);">
            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #E2E8F0; padding-bottom: 12px; margin-bottom: 15px;">
                <div>
                    <h2 id="modal-shipment-no" style="font-size: 1.3rem; font-weight: 900; font-family: monospace; color: #0F172A; margin: 0;">QX-8829-US</h2>
                    <span id="modal-shipment-status" style="font-size: 0.75rem; font-weight: 800; text-transform: uppercase; padding: 2px 8px; border-radius: 999px; background: #DBEAFE; color: #1D4ED8;">IN TRANSIT</span>
                </div>
                <button id="btn-close-shipment-modal" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #64748B;">&times;</button>
            </div>
            
            <div id="modal-shipment-content">
                <!-- Loaded dynamically -->
            </div>
        </div>
    </div>

</main>

<style>
.dash-tab-btn {
    display: flex;
    align-items: center;
    width: 100%;
    padding: 0.75rem 0.85rem;
    border: none;
    background: transparent;
    color: #475569;
    font-weight: 700;
    font-size: 0.85rem;
    border-radius: 6px;
    cursor: pointer;
    text-align: left;
    transition: all 0.15s ease;
}
.dash-tab-btn:hover {
    background: #F1F5F9;
    color: #0F172A;
}
.dash-tab-btn.active {
    background: #0F172A;
    color: #FFFFFF;
}
.dash-view-pane {
    display: none;
}
.dash-view-pane.active {
    display: block;
}
@media (max-width: 768px) {
    .dashboard-grid-container {
        grid-template-columns: 1fr !important;
    }
    .support-grid {
        grid-template-columns: 1fr !important;
    }
}
</style>

<script src="/assets/js/dashboard.js?v=1.0.0" defer></script>

<?php
get_footer();
