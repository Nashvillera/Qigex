<?php
/**
 * Quote Form Processing & Management Architecture
 * Phase 6: Quote, Contact & WhatsApp
 *
 * @package Haivora_Logistics
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Verify Nonce Security Token for Quote Form
 */
function haivora_verify_quote_nonce($nonce) {
    return wp_verify_nonce($nonce, 'haivora_quote_action');
}

/**
 * Check Spam Honeypot Protection
 */
function haivora_check_honeypot($honeypot_value) {
    // If honeypot is filled, it's a bot submission
    return !empty($honeypot_value);
}

/**
 * Validate Quote Request Form Fields
 */
function haivora_validate_quote_data($data) {
    $errors = array();

    if (empty($data['full_name'])) {
        $errors[] = __('Full Name is required.', 'haivora-logistics');
    }

    if (empty($data['email']) || !is_email($data['email'])) {
        $errors[] = __('A valid Email Address is required.', 'haivora-logistics');
    }

    if (empty($data['phone'])) {
        $errors[] = __('Phone Number is required.', 'haivora-logistics');
    }

    if (empty($data['origin'])) {
        $errors[] = __('Origin city/country is required.', 'haivora-logistics');
    }

    if (empty($data['destination'])) {
        $errors[] = __('Destination city/country is required.', 'haivora-logistics');
    }

    if (empty($data['shipment_type'])) {
        $errors[] = __('Please select a Shipment Type.', 'haivora-logistics');
    }

    if (empty($data['package_type'])) {
        $errors[] = __('Please select a Package Type.', 'haivora-logistics');
    }

    if (empty($data['weight']) || !is_numeric($data['weight']) || floatval($data['weight']) <= 0) {
        $errors[] = __('Please enter a valid weight in kilograms.', 'haivora-logistics');
    }

    return $errors;
}

/**
 * Sanitize Quote Request Data Array
 */
function haivora_sanitize_quote_data($raw_data) {
    return array(
        'id'               => isset($raw_data['id']) ? sanitize_text_field($raw_data['id']) : 'QT-' . date('Y') . '-' . rand(1000, 9999),
        'full_name'        => sanitize_text_field($raw_data['full_name']),
        'email'            => sanitize_email($raw_data['email']),
        'phone'            => sanitize_text_field($raw_data['phone']),
        'origin'           => sanitize_text_field($raw_data['origin']),
        'destination'      => sanitize_text_field($raw_data['destination']),
        'shipment_type'    => sanitize_text_field($raw_data['shipment_type']),
        'package_type'     => sanitize_text_field($raw_data['package_type']),
        'weight'           => floatval($raw_data['weight']),
        'dimensions'       => sanitize_text_field(isset($raw_data['dimensions']) ? $raw_data['dimensions'] : 'N/A'),
        'shipping_method'  => sanitize_text_field(isset($raw_data['shipping_method']) ? $raw_data['shipping_method'] : 'Standard Priority'),
        'pickup_date'      => sanitize_text_field(isset($raw_data['pickup_date']) ? $raw_data['pickup_date'] : date('Y-m-d')),
        'additional_info'  => sanitize_textarea_field(isset($raw_data['additional_info']) ? $raw_data['additional_info'] : ''),
        'date_submitted'   => current_time('mysql'),
        'status'           => isset($raw_data['status']) ? sanitize_text_field($raw_data['status']) : 'New', // New, Reviewing, Quoted, Accepted, Rejected, Completed
        'quoted_rate'      => isset($raw_data['quoted_rate']) ? sanitize_text_field($raw_data['quoted_rate']) : '',
        'admin_notes'      => isset($raw_data['admin_notes']) ? sanitize_textarea_field($raw_data['admin_notes']) : '',
    );
}

/**
 * Save Quote Request Record
 */
function haivora_save_quote_request($quote_data) {
    $quotes = get_option('haivora_quote_requests', array());
    if (!is_array($quotes)) {
        $quotes = array();
    }

    // Insert at beginning of array
    array_unshift($quotes, $quote_data);

    // Save back to options table
    update_option('haivora_quote_requests', $quotes);

    // Dispatch Email Notifications
    if (function_exists('haivora_send_quote_email_notifications')) {
        haivora_send_quote_email_notifications($quote_data);
    }

    return $quote_data;
}

/**
 * Retrieve All Quote Requests
 */
function haivora_get_all_quote_requests($status_filter = 'all') {
    $quotes = get_option('haivora_quote_requests', array());
    if (!is_array($quotes) || empty($quotes)) {
        // Sample default quote for initial preview
        return array(
            array(
                'id'               => 'QT-2026-8819',
                'full_name'        => 'Robert Vance',
                'email'            => 'r.vance@vancerefrigeration.com',
                'phone'            => '+1 (555) 234-5678',
                'origin'           => 'Chicago, IL, USA',
                'destination'      => 'Hamburg, Germany',
                'shipment_type'    => 'Sea Freight (FCL / LCL)',
                'package_type'     => 'Pallets',
                'weight'           => 1250,
                'dimensions'       => '240 x 120 x 160 cm',
                'shipping_method'  => 'Standard Priority',
                'pickup_date'      => '2026-08-20',
                'additional_info'  => 'Temperature controlled refrigerated cargo container needed.',
                'date_submitted'   => '2026-08-14 01:15:00',
                'status'           => 'New',
                'quoted_rate'      => '',
                'admin_notes'      => ''
            )
        );
    }

    if ($status_filter !== 'all') {
        $quotes = array_filter($quotes, function($q) use ($status_filter) {
            return strtolower($q['status']) === strtolower($status_filter);
        });
    }

    return array_values($quotes);
}

/**
 * Update Quote Status, Rate, and Dispatch Email
 */
function haivora_update_quote_request_status($quote_id, $new_status, $quoted_rate = '', $admin_notes = '') {
    $quotes = get_option('haivora_quote_requests', array());
    if (!is_array($quotes)) return false;

    $updated_quote = null;
    foreach ($quotes as &$q) {
        if ($q['id'] === $quote_id) {
            $q['status'] = sanitize_text_field($new_status);
            if ($quoted_rate !== '') {
                $q['quoted_rate'] = sanitize_text_field($quoted_rate);
            }
            if ($admin_notes !== '') {
                $q['admin_notes'] = sanitize_textarea_field($admin_notes);
            }
            $updated_quote = $q;
            break;
        }
    }

    if ($updated_quote) {
        update_option('haivora_quote_requests', $quotes);

        // If status changed to Quoted, send customer email notification
        if ($new_status === 'Quoted' && !empty($quoted_rate) && function_exists('haivora_send_quote_rate_email')) {
            haivora_send_quote_rate_email($updated_quote, $quoted_rate, $admin_notes);
        }

        return $updated_quote;
    }

    return false;
}
