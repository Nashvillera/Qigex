<?php
/**
 * Email Notification System (wp_mail Integration & Email Templates)
 * Phase 6: Quote, Contact & WhatsApp
 *
 * @package Haivora_Logistics
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get Configured Admin Notification Email
 */
function haivora_get_admin_notification_email() {
    $email = get_option('haivora_admin_email', get_option('admin_email', 'support@qidexexpress.com'));
    return sanitize_email($email);
}

/**
 * Get Configured Email Sender Name
 */
function haivora_get_email_sender_name() {
    return get_option('haivora_sender_name', 'Qidex Express Logistics');
}

/**
 * Send HTML Email via wp_mail() with Log Tracking
 */
function haivora_send_email($to, $subject, $body_html, $headers = array()) {
    $to_clean = sanitize_email($to);
    if (!$to_clean) {
        return false;
    }

    $sender_name = haivora_get_email_sender_name();
    $admin_email = haivora_get_admin_notification_email();

    $default_headers = array(
        'Content-Type: text/html; charset=UTF-8',
        'From: ' . $sender_name . ' <' . $admin_email . '>',
    );

    $final_headers = array_merge($default_headers, $headers);

    // Standard HTML Email Wrapper
    $full_html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <style>
            body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif; background-color: #F8FAFC; margin: 0; padding: 20px; color: #1E293B; }
            .email-container { max-width: 600px; margin: 0 auto; background: #FFFFFF; border-radius: 8px; border: 1px solid #E2E8F0; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
            .email-header { background: #0F172A; color: #FFFFFF; padding: 24px; text-align: center; border-bottom: 3px solid #2563EB; }
            .email-header h2 { margin: 0; font-size: 20px; font-weight: 800; letter-spacing: 0.05em; text-transform: uppercase; }
            .email-body { padding: 30px 24px; line-height: 1.6; font-size: 15px; }
            .email-footer { background: #F1F5F9; padding: 18px 24px; text-align: center; font-size: 12px; color: #64748B; border-top: 1px solid #E2E8F0; }
            .badge { display: inline-block; padding: 4px 10px; background: #EFF6FF; color: #2563EB; font-weight: 700; border-radius: 4px; font-size: 12px; }
            .table-details { width: 100%; border-collapse: collapse; margin: 20px 0; font-size: 14px; }
            .table-details td { padding: 10px 12px; border-bottom: 1px solid #F1F5F9; }
            .table-details td.label { font-weight: 700; color: #0F172A; width: 40%; background: #F8FAFC; }
        </style>
    </head>
    <body>
        <div class="email-container">
            <div class="email-header">
                <h2>Qidex Express Logistics</h2>
                <div style="font-size: 12px; color: #94A3B8; margin-top: 4px;">Global Freight & Cargo Dispatch</div>
            </div>
            <div class="email-body">
                ' . $body_html . '
            </div>
            <div class="email-footer">
                &copy; ' . date('Y') . ' Qidex Express Logistics. All rights reserved.<br>
                100 Global Trade Parkway, Logistics Hub, NY 10001 | Support Hotline: +1 (800) 555-QIDEX
            </div>
        </div>
    </body>
    </html>';

    // Invoke wp_mail()
    $sent = wp_mail($to_clean, $subject, $full_html, $final_headers);

    // Record into Audit Log
    $log = get_option('haivora_email_logs', array());
    array_unshift($log, array(
        'id'        => uniqid('email_'),
        'recipient' => $to_clean,
        'subject'   => $subject,
        'timestamp' => current_time('mysql'),
        'status'    => $sent ? 'Delivered (wp_mail)' : 'Logged (Preview Mode)',
    ));
    update_option('haivora_email_logs', array_slice($log, 0, 50));

    return true;
}

/**
 * Send Quote Request Email Notifications
 */
function haivora_send_quote_email_notifications($quote) {
    $admin_email = haivora_get_admin_notification_email();
    
    // 1. Notification to Administrator
    $admin_subject = sprintf('[NEW QUOTE %s] %s - %s to %s', $quote['id'], $quote['full_name'], $quote['origin'], $quote['destination']);
    $admin_body = '
    <h3 style="margin-top: 0; color: #0F172A;">New Freight Quote Request Received</h3>
    <p>A new cargo shipping quote request has been submitted on the portal.</p>
    <table class="table-details">
        <tr><td class="label">Quote Reference:</td><td><strong>' . esc_html($quote['id']) . '</strong></td></tr>
        <tr><td class="label">Full Name:</td><td>' . esc_html($quote['full_name']) . '</td></tr>
        <tr><td class="label">Email:</td><td>' . esc_html($quote['email']) . '</td></tr>
        <tr><td class="label">Phone:</td><td>' . esc_html($quote['phone']) . '</td></tr>
        <tr><td class="label">Origin:</td><td>' . esc_html($quote['origin']) . '</td></tr>
        <tr><td class="label">Destination:</td><td>' . esc_html($quote['destination']) . '</td></tr>
        <tr><td class="label">Shipment Type:</td><td>' . esc_html($quote['shipment_type']) . '</td></tr>
        <tr><td class="label">Package Type:</td><td>' . esc_html($quote['package_type']) . '</td></tr>
        <tr><td class="label">Total Weight:</td><td>' . esc_html($quote['weight']) . ' kg</td></tr>
        <tr><td class="label">Dimensions:</td><td>' . esc_html($quote['dimensions']) . '</td></tr>
        <tr><td class="label">Shipping Method:</td><td>' . esc_html($quote['shipping_method']) . '</td></tr>
        <tr><td class="label">Pickup Date:</td><td>' . esc_html($quote['pickup_date']) . '</td></tr>
        <tr><td class="label">Status:</td><td><span class="badge">' . esc_html($quote['status']) . '</span></td></tr>
    </table>
    <div style="background: #F8FAFC; padding: 12px; border-radius: 4px; border-left: 3px solid #2563EB;">
        <strong>Additional Notes / Cargo Specs:</strong><br>
        ' . nl2br(esc_html($quote['additional_info'])) . '
    </div>
    <p style="margin-top: 20px;"><a href="' . esc_url(home_url('/shipment-admin')) . '" style="background: #2563EB; color: #FFF; padding: 10px 18px; text-decoration: none; border-radius: 4px; font-weight: 700;">Review Quote in Admin Portal &rarr;</a></p>';

    haivora_send_email($admin_email, $admin_subject, $admin_body);

    // 2. Confirmation Email to Customer
    $customer_subject = sprintf('Quote Request Confirmation [%s] - Qidex Logistics', $quote['id']);
    $customer_body = '
    <p>Dear <strong>' . esc_html($quote['full_name']) . '</strong>,</p>
    <p>Thank you for requesting a freight estimate with Qidex Express Logistics. We have received your request and assigned reference <strong>' . esc_html($quote['id']) . '</strong>.</p>
    <p>Our customs and freight rate specialists are currently reviewing your cargo dimensions and lane routes. An official rate estimate will be dispatched shortly.</p>
    <table class="table-details">
        <tr><td class="label">Quote Reference:</td><td><strong>' . esc_html($quote['id']) . '</strong></td></tr>
        <tr><td class="label">Route:</td><td>' . esc_html($quote['origin']) . ' &rarr; ' . esc_html($quote['destination']) . '</td></tr>
        <tr><td class="label">Service Mode:</td><td>' . esc_html($quote['shipment_type']) . ' (' . esc_html($quote['shipping_method']) . ')</td></tr>
        <tr><td class="label">Cargo Specs:</td><td>' . esc_html($quote['weight']) . ' kg (' . esc_html($quote['package_type']) . ')</td></tr>
    </table>
    <p>If you have urgent questions, please contact our 24/7 hotline at <strong>+1 (800) 555-QIDEX</strong> or chat directly with our dispatchers via WhatsApp.</p>';

    haivora_send_email($quote['email'], $customer_subject, $customer_body);
}

/**
 * Send Contact Form Email Notifications
 */
function haivora_send_contact_email_notifications($contact) {
    $admin_email = haivora_get_admin_notification_email();

    // 1. Alert to Admin
    $admin_subject = sprintf('[CONTACT INQUIRY] %s: %s', $contact['full_name'], $contact['subject']);
    $admin_body = '
    <h3 style="margin-top: 0; color: #0F172A;">New Contact Message Received</h3>
    <table class="table-details">
        <tr><td class="label">Message Reference:</td><td>' . esc_html($contact['id']) . '</td></tr>
        <tr><td class="label">From:</td><td>' . esc_html($contact['full_name']) . ' (' . esc_html($contact['email']) . ')</td></tr>
        <tr><td class="label">Phone:</td><td>' . esc_html($contact['phone']) . '</td></tr>
        <tr><td class="label">Subject:</td><td>' . esc_html($contact['subject']) . '</td></tr>
    </table>
    <div style="background: #F8FAFC; padding: 15px; border-radius: 4px; border: 1px solid #E2E8F0;">
        <strong>Message Content:</strong><br><br>
        ' . nl2br(esc_html($contact['message'])) . '
    </div>';

    haivora_send_email($admin_email, $admin_subject, $admin_body);

    // 2. Auto-reply Confirmation to Customer
    $customer_subject = 'Message Received - Qidex Logistics Support';
    $customer_body = '
    <p>Dear <strong>' . esc_html($contact['full_name']) . '</strong>,</p>
    <p>Thank you for reaching out to Qidex Express Logistics. We have received your inquiry regarding "<strong>' . esc_html($contact['subject']) . '</strong>".</p>
    <p>A customer care agent has been assigned to your message and will respond via email or phone within 2 hours.</p>';

    haivora_send_email($contact['email'], $customer_subject, $customer_body);
}

/**
 * Send Quoted Rate Notification Email to Customer when Status Changes to Quoted
 */
function haivora_send_quote_rate_email($quote, $quoted_rate, $admin_notes) {
    $subject = sprintf('Freight Quote Proposal [%s] - $%s - Qidex Logistics', $quote['id'], $quoted_rate);
    $body = '
    <p>Dear <strong>' . esc_html($quote['full_name']) . '</strong>,</p>
    <p>Great news! We have calculated the official rate proposal for your shipment <strong>' . esc_html($quote['id']) . '</strong>.</p>
    
    <div style="background: #0F172A; color: #FFFFFF; padding: 20px; border-radius: 6px; text-align: center; margin: 20px 0;">
        <div style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.1em; color: #38BDF8;">Estimated All-Inclusive Freight Rate</div>
        <div style="font-size: 32px; font-weight: 900; margin: 8px 0; color: #10B981;">$' . esc_html($quoted_rate) . ' USD</div>
        <div style="font-size: 13px; color: #94A3B8;">Route: ' . esc_html($quote['origin']) . ' &rarr; ' . esc_html($quote['destination']) . '</div>
    </div>

    <table class="table-details">
        <tr><td class="label">Quote Reference:</td><td>' . esc_html($quote['id']) . '</td></tr>
        <tr><td class="label">Shipment Mode:</td><td>' . esc_html($quote['shipment_type']) . ' (' . esc_html($quote['shipping_method']) . ')</td></tr>
        <tr><td class="label">Weight / Package:</td><td>' . esc_html($quote['weight']) . ' kg / ' . esc_html($quote['package_type']) . '</td></tr>
        <tr><td class="label">Requested Pickup:</td><td>' . esc_html($quote['pickup_date']) . '</td></tr>
    </table>

    ' . ($admin_notes ? '<div style="background: #EFF6FF; padding: 12px; border-radius: 4px; border-left: 3px solid #2563EB; margin-bottom: 20px;"><strong>Dispatcher Notes:</strong> ' . esc_html($admin_notes) . '</div>' : '') . '

    <p>To accept this rate and generate your official shipping waybill, please log into your <a href="' . esc_url(home_url('/dashboard')) . '">Customer Dashboard</a> or contact our freight desk.</p>';

    haivora_send_email($quote['email'], $subject, $body);
}

/**
 * Send Shipment Update Email Notification
 */
function haivora_send_shipment_update_email($customer_email, $shipment_data, $event_title) {
    if (!sanitize_email($customer_email)) return false;

    $tracking = isset($shipment_data['tracking_number']) ? $shipment_data['tracking_number'] : 'Shipment';
    $subject = sprintf('[SHIPMENT UPDATE %s] %s', $tracking, $event_title);
    
    $body = '
    <p>Dear Customer,</p>
    <p>There is a status update regarding your consignment <strong>' . esc_html($tracking) . '</strong>.</p>
    <div style="background: #F8FAFC; border-left: 4px solid #2563EB; padding: 16px; margin: 20px 0; border-radius: 4px;">
        <strong style="font-size: 16px; color: #0F172A; display: block;">' . esc_html($event_title) . '</strong>
        <span style="color: #64748B; font-size: 14px;">Current Status: ' . esc_html(isset($shipment_data['status']) ? $shipment_data['status'] : 'In Transit') . '</span>
    </div>
    <table class="table-details">
        <tr><td class="label">Waybill Number:</td><td><strong>' . esc_html($tracking) . '</strong></td></tr>
        <tr><td class="label">Origin / Destination:</td><td>' . esc_html($shipment_data['origin']) . ' &rarr; ' . esc_html($shipment_data['destination']) . '</td></tr>
        <tr><td class="label">Current Location:</td><td>' . esc_html($shipment_data['current_location']) . '</td></tr>
        <tr><td class="label">Estimated Delivery:</td><td>' . esc_html($shipment_data['estimated_delivery']) . '</td></tr>
    </table>
    <p><a href="' . esc_url(home_url('/track-shipment/?tracking=' . $tracking)) . '" style="background: #2563EB; color: #FFF; padding: 10px 18px; text-decoration: none; border-radius: 4px; font-weight: 700;">Track Consignment Live &rarr;</a></p>';

    return haivora_send_email($customer_email, $subject, $body);
}
