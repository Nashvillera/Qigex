<?php
/**
 * Contact Form Processing & Inbox Management Architecture
 * Phase 6: Quote, Contact & WhatsApp
 *
 * @package Haivora_Logistics
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Verify Nonce Security Token for Contact Form
 */
function haivora_verify_contact_nonce($nonce) {
    return wp_verify_nonce($nonce, 'haivora_contact_action');
}

/**
 * Validate Contact Form Fields
 */
function haivora_validate_contact_data($data) {
    $errors = array();

    if (empty($data['full_name'])) {
        $errors[] = __('Full Name is required.', 'haivora-logistics');
    }

    if (empty($data['email']) || !is_email($data['email'])) {
        $errors[] = __('A valid Email Address is required.', 'haivora-logistics');
    }

    if (empty($data['subject'])) {
        $errors[] = __('Subject is required.', 'haivora-logistics');
    }

    if (empty($data['message'])) {
        $errors[] = __('Message content is required.', 'haivora-logistics');
    }

    return $errors;
}

/**
 * Sanitize Contact Form Data Array
 */
function haivora_sanitize_contact_data($raw_data) {
    return array(
        'id'             => isset($raw_data['id']) ? sanitize_text_field($raw_data['id']) : 'MSG-' . date('Y') . '-' . rand(100, 999),
        'full_name'      => sanitize_text_field($raw_data['full_name']),
        'email'          => sanitize_email($raw_data['email']),
        'phone'          => sanitize_text_field(isset($raw_data['phone']) ? $raw_data['phone'] : ''),
        'subject'        => sanitize_text_field($raw_data['subject']),
        'message'        => sanitize_textarea_field($raw_data['message']),
        'date_submitted' => current_time('mysql'),
        'status'         => isset($raw_data['status']) ? sanitize_text_field($raw_data['status']) : 'Unread', // Unread, Replied, Archived
    );
}

/**
 * Save Contact Message Record
 */
function haivora_save_contact_message($contact_data) {
    $messages = get_option('haivora_contact_messages', array());
    if (!is_array($messages)) {
        $messages = array();
    }

    array_unshift($messages, $contact_data);
    update_option('haivora_contact_messages', $messages);

    // Trigger Email Dispatch
    if (function_exists('haivora_send_contact_email_notifications')) {
        haivora_send_contact_email_notifications($contact_data);
    }

    return $contact_data;
}

/**
 * Get Contact Messages Inbox
 */
function haivora_get_contact_messages($status_filter = 'all') {
    $messages = get_option('haivora_contact_messages', array());
    if (!is_array($messages) || empty($messages)) {
        return array(
            array(
                'id'             => 'MSG-2026-101',
                'full_name'      => 'Elena Rostova',
                'email'          => 'elena@globalmachinery.eu',
                'phone'          => '+49 30 98765432',
                'subject'        => 'Customs Clearance Help for PVG Shipment',
                'message'        => 'We need assistance clarifying transit documentation for our high-value industrial shipment arriving at Frankfurt terminal next week.',
                'date_submitted' => '2026-08-13 18:40:00',
                'status'         => 'Unread'
            )
        );
    }

    if ($status_filter !== 'all') {
        $messages = array_filter($messages, function($m) use ($status_filter) {
            return strtolower($m['status']) === strtolower($status_filter);
        });
    }

    return array_values($messages);
}

/**
 * Update Contact Message Status
 */
function haivora_update_contact_status($msg_id, $new_status) {
    $messages = get_option('haivora_contact_messages', array());
    if (!is_array($messages)) return false;

    foreach ($messages as &$m) {
        if ($m['id'] === $msg_id) {
            $m['status'] = sanitize_text_field($new_status);
            update_option('haivora_contact_messages', $messages);
            return $m;
        }
    }

    return false;
}
