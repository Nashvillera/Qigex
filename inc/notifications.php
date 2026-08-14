<?php
/**
 * Shipment Notifications Architecture & Event Dispatcher
 * Phase 5: Customer Accounts and Dashboard
 *
 * @package Haivora_Logistics
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get Customer Notification Preferences
 */
function haivora_get_notification_preferences($user_id) {
    return array(
        'email'    => get_user_meta($user_id, 'notify_email', true) !== '0',
        'whatsapp' => get_user_meta($user_id, 'notify_whatsapp', true) !== '0',
        'sms'      => get_user_meta($user_id, 'notify_sms', true) === '1',
    );
}

/**
 * Update Customer Notification Preferences
 */
function haivora_update_notification_preferences($user_id, $prefs) {
    if (isset($prefs['email'])) {
        update_user_meta($user_id, 'notify_email', $prefs['email'] ? '1' : '0');
    }
    if (isset($prefs['whatsapp'])) {
        update_user_meta($user_id, 'notify_whatsapp', $prefs['whatsapp'] ? '1' : '0');
    }
    if (isset($prefs['sms'])) {
        update_user_meta($user_id, 'notify_sms', $prefs['sms'] ? '1' : '0');
    }
    return true;
}

/**
 * Log and Dispatch Shipment Milestone Notification
 */
function haivora_dispatch_shipment_notification($user_id, $shipment_data, $event_title, $event_description = '') {
    if (!$user_id) return false;

    $prefs = haivora_get_notification_preferences($user_id);
    $tracking = isset($shipment_data['tracking_number']) ? $shipment_data['tracking_number'] : 'Shipment';
    $timestamp = current_time('mysql');

    // In-App Notification Log Entry
    $notification_entry = array(
        'id'          => uniqid('notif_'),
        'tracking_no' => $tracking,
        'title'       => $event_title,
        'message'     => $event_description ? $event_description : sprintf(__('Update recorded for %s: %s', 'haivora-logistics'), $tracking, $event_title),
        'timestamp'   => $timestamp,
        'read'        => false,
        'channels'    => array(
            'email'    => $prefs['email'],
            'whatsapp' => $prefs['whatsapp'],
            'sms'      => $prefs['sms'],
        )
    );

    // Append to User Notification History array
    $log = get_user_meta($user_id, 'haivora_notifications_log', true);
    if (!is_array($log)) {
        $log = array();
    }
    array_unshift($log, $notification_entry);
    // Keep last 30 notifications
    $log = array_slice($log, 0, 30);
    update_user_meta($user_id, 'haivora_notifications_log', $log);

    // Dispatch Action Hooks for Future Third-Party Providers
    if ($prefs['email']) {
        /**
         * Action Hook: Future Email Dispatcher (SendGrid / WP Mail)
         */
        do_action('haivora_trigger_email_notification', $user_id, $shipment_data, $event_title);
    }

    if ($prefs['whatsapp']) {
        /**
         * Action Hook: Future WhatsApp Dispatcher (Twilio / Meta API)
         */
        do_action('haivora_trigger_whatsapp_notification', $user_id, $shipment_data, $event_title);
    }

    if ($prefs['sms']) {
        /**
         * Action Hook: Future SMS Dispatcher (Twilio / AWS SNS)
         */
        do_action('haivora_trigger_sms_notification', $user_id, $shipment_data, $event_title);
    }

    return $notification_entry;
}

/**
 * Retrieve User Notifications Feed
 */
function haivora_get_user_notifications($user_id) {
    $log = get_user_meta($user_id, 'haivora_notifications_log', true);
    if (!is_array($log)) {
        // Return default welcome notification for new users
        return array(
            array(
                'id'          => 'welcome_1',
                'tracking_no' => 'QIDEX-PORTAL',
                'title'       => __('Welcome to Qidex Express Portal', 'haivora-logistics'),
                'message'     => __('Your account has been configured. Automated email & mobile tracking updates are active.', 'haivora-logistics'),
                'timestamp'   => current_time('mysql'),
                'read'        => false,
                'channels'    => array('email' => true, 'whatsapp' => true, 'sms' => false)
            )
        );
    }
    return $log;
}
