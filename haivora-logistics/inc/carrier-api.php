<?php
/**
 * Carrier Logistics API Architecture
 * 
 * Abstraction layer for third-party logistics carrier APIs (e.g. DHL, FedEx, UPS, Aramex).
 * STATUS: READY FOR INTEGRATION
 * 
 * @package Haivora_Logistics
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get Carrier API Configuration
 * Retrieves API credentials from wp-config.php constants or options table.
 * Secrets are never stored in public theme files.
 */
function haivora_get_carrier_api_config() {
    return array(
        'provider'   => defined('HAIVORA_CARRIER_PROVIDER') ? HAIVORA_CARRIER_PROVIDER : get_option('haivora_carrier_provider', 'demo_carrier'),
        'api_url'    => defined('HAIVORA_CARRIER_API_URL') ? HAIVORA_CARRIER_API_URL : get_option('haivora_carrier_api_url', 'https://api.carrier-service.com/v1'),
        'api_key'    => defined('HAIVORA_CARRIER_API_KEY') ? HAIVORA_CARRIER_API_KEY : get_option('haivora_carrier_api_key', ''),
        'api_secret' => defined('HAIVORA_CARRIER_API_SECRET') ? HAIVORA_CARRIER_API_SECRET : get_option('haivora_carrier_api_secret', ''),
        'mode'       => defined('HAIVORA_CARRIER_MODE') ? HAIVORA_CARRIER_MODE : get_option('haivora_carrier_mode', 'sandbox'),
    );
}

/**
 * Create Shipment via Carrier API
 * 
 * @param array $data Shipment parameters
 * @return array|WP_Error Response object containing tracking_number and carrier details
 */
function haivora_api_create_shipment($data) {
    $config = haivora_get_carrier_api_config();

    // Validation
    if (empty($data['sender_name']) || empty($data['receiver_name']) || empty($data['origin']) || empty($data['destination'])) {
        return new WP_Error('missing_parameters', __('Required shipment parameters are missing.', 'haivora-logistics'), array('status' => 400));
    }

    // Generate unique tracking code format if not provided
    $tracking_number = !empty($data['tracking_number']) ? sanitize_text_field($data['tracking_number']) : 'HV-' . date('Y') . '-' . wp_rand(100000, 999999);

    // If live API credentials exist, dispatch remote HTTP POST request
    if (!empty($config['api_key']) && !empty($config['api_url']) && $config['provider'] !== 'demo_carrier') {
        $response = wp_remote_post(rtrim($config['api_url'], '/') . '/shipments', array(
            'headers' => array(
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer ' . $config['api_key'],
                'X-Api-Secret'  => $config['api_secret'],
            ),
            'body'    => wp_json_encode($data),
            'timeout' => 15,
        ));

        if (is_wp_error($response)) {
            return $response;
        }

        $body = wp_remote_retrieve_body($response);
        $result = json_decode($body, true);
        if (!empty($result['tracking_number'])) {
            return $result;
        }
    }

    // Default Architecture Standard Response (Ready for Integration)
    return array(
        'success'         => true,
        'integration'     => 'READY_FOR_INTEGRATION',
        'provider'        => $config['provider'],
        'tracking_number' => $tracking_number,
        'status'          => !empty($data['status']) ? sanitize_text_field($data['status']) : 'Pending',
        'origin'          => sanitize_text_field($data['origin']),
        'destination'     => sanitize_text_field($data['destination']),
        'created_at'      => current_time('mysql'),
    );
}

/**
 * Get Shipment Details
 * 
 * @param string $tracking_number
 * @return array|WP_Error
 */
function haivora_api_get_shipment($tracking_number) {
    $tracking_number = sanitize_text_field($tracking_number);
    if (empty($tracking_number)) {
        return new WP_Error('invalid_tracking', __('Tracking number is required.', 'haivora-logistics'), array('status' => 400));
    }

    $shipment = haivora_get_shipment_by_tracking($tracking_number);
    if (!$shipment) {
        return new WP_Error('shipment_not_found', __('Shipment record not found.', 'haivora-logistics'), array('status' => 404));
    }

    return $shipment;
}

/**
 * Update Shipment Details
 * 
 * @param string $tracking_number
 * @param array $update_data
 * @return array|WP_Error
 */
function haivora_api_update_shipment($tracking_number, $update_data) {
    $tracking_number = sanitize_text_field($tracking_number);
    $shipment = haivora_get_shipment_by_tracking($tracking_number);

    if (!$shipment) {
        return new WP_Error('shipment_not_found', __('Shipment not found for update.', 'haivora-logistics'), array('status' => 404));
    }

    // Update fields
    if (isset($update_data['status'])) {
        update_post_meta($shipment['id'], '_haivora_status', sanitize_text_field($update_data['status']));
    }
    if (isset($update_data['current_location'])) {
        update_post_meta($shipment['id'], '_haivora_current_location', sanitize_text_field($update_data['current_location']));
    }
    if (isset($update_data['estimated_delivery'])) {
        update_post_meta($shipment['id'], '_haivora_estimated_delivery', sanitize_text_field($update_data['estimated_delivery']));
    }

    return haivora_get_shipment_by_tracking($tracking_number);
}

/**
 * Track Shipment (Public Sanitized Response)
 * Strip sensitive PII for public tracking queries.
 * 
 * @param string $tracking_number
 * @return array|WP_Error
 */
function haivora_api_track_shipment($tracking_number) {
    $shipment = haivora_api_get_shipment($tracking_number);
    if (is_wp_error($shipment)) {
        return $shipment;
    }

    // Public Sanitization - Hide sensitive address details and receiver phone numbers
    return array(
        'tracking_number'    => $shipment['tracking_number'],
        'status'             => $shipment['status'],
        'origin'             => $shipment['origin'],
        'destination'        => $shipment['destination'],
        'current_location'   => $shipment['current_location'],
        'estimated_delivery' => $shipment['estimated_delivery'],
        'weight'             => $shipment['weight'],
        'events'             => !empty($shipment['events']) ? $shipment['events'] : array(),
        'last_updated'       => !empty($shipment['last_updated']) ? $shipment['last_updated'] : current_time('mysql'),
    );
}
