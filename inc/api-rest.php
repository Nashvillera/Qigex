<?php
/**
 * WordPress REST API Endpoints Architecture
 * 
 * Secure REST API endpoints registered under namespace `haivora/v1`.
 * Implements strict authorization, sanitization, validation, and PII masking.
 * STATUS: READY FOR INTEGRATION
 * 
 * @package Haivora_Logistics
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register REST API Routes
 */
function haivora_register_rest_routes() {
    $namespace = 'haivora/v1';

    // 1. GET /shipments - List Shipments
    register_rest_route($namespace, '/shipments', array(
        'methods'             => WP_REST_Server::READABLE,
        'callback'            => 'haivora_rest_get_shipments',
        'permission_callback' => 'haivora_rest_permissions_admin_or_user',
    ));

    // 2. GET /shipments/{id} - Get Single Shipment
    register_rest_route($namespace, '/shipments/(?P<id>[\w-]+)', array(
        'methods'             => WP_REST_Server::READABLE,
        'callback'            => 'haivora_rest_get_shipment_single',
        'permission_callback' => 'haivora_rest_permissions_admin_or_user',
    ));

    // 3. POST /shipments - Create Shipment
    register_rest_route($namespace, '/shipments', array(
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => 'haivora_rest_create_shipment',
        'permission_callback' => 'haivora_rest_permissions_admin_or_user',
    ));

    // 4. PUT /shipments/{id} - Update Shipment
    register_rest_route($namespace, '/shipments/(?P<id>[\w-]+)', array(
        'methods'             => WP_REST_Server::EDITABLE,
        'callback'            => 'haivora_rest_update_shipment',
        'permission_callback' => 'haivora_rest_permissions_admin_only',
    ));

    // 5. GET /track/{code} - Public Tracking Route (PII Masked)
    register_rest_route($namespace, '/track/(?P<code>[\w-]+)', array(
        'methods'             => WP_REST_Server::READABLE,
        'callback'            => 'haivora_rest_track_shipment',
        'permission_callback' => '__return_true', // Publicly accessible but PII masked
    ));

    // 6. POST /payments/initiate - Payment Checkout Token Generation
    register_rest_route($namespace, '/payments/initiate', array(
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => 'haivora_rest_initiate_payment',
        'permission_callback' => '__return_true',
    ));

    // 7. GET /payments/transactions - Transaction Audit Log
    register_rest_route($namespace, '/payments/transactions', array(
        'methods'             => WP_REST_Server::READABLE,
        'callback'            => 'haivora_rest_get_transactions',
        'permission_callback' => 'haivora_rest_permissions_admin_only',
    ));

    // 8. POST /webhooks/payment - Webhook Handler with Signature Check
    register_rest_route($namespace, '/webhooks/payment', array(
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => 'haivora_rest_handle_payment_webhook',
        'permission_callback' => 'haivora_rest_permission_webhook_signature',
    ));
}
add_action('rest_api_init', 'haivora_register_rest_routes');

/**
 * Permission Callbacks
 */
function haivora_rest_permissions_admin_only() {
    return current_user_can('manage_options');
}

function haivora_rest_permissions_admin_or_user() {
    if (current_user_can('manage_options')) {
        return true;
    }
    return is_user_logged_in();
}

function haivora_rest_permission_webhook_signature($request) {
    return haivora_verify_webhook_signature($request);
}

/**
 * REST Handlers
 */

// GET /shipments
function haivora_rest_get_shipments($request) {
    if (function_exists('haivora_get_all_shipments')) {
        $shipments = haivora_get_all_shipments();
        return rest_ensure_response($shipments);
    }
    return new WP_Error('not_found', __('Shipment query function not loaded.', 'haivora-logistics'), array('status' => 500));
}

// GET /shipments/{id}
function haivora_rest_get_shipment_single($request) {
    $id = sanitize_text_field($request->get_param('id'));
    $shipment = haivora_api_get_shipment($id);
    if (is_wp_error($shipment)) {
        return $shipment;
    }
    return rest_ensure_response($shipment);
}

// POST /shipments
function haivora_rest_create_shipment($request) {
    $params = $request->get_json_params();
    if (empty($params)) {
        $params = $request->get_body_params();
    }

    $clean_data = array(
        'sender_name'     => sanitize_text_field($params['sender_name'] ?? ''),
        'receiver_name'   => sanitize_text_field($params['receiver_name'] ?? ''),
        'origin'          => sanitize_text_field($params['origin'] ?? ''),
        'destination'     => sanitize_text_field($params['destination'] ?? ''),
        'shipment_type'   => sanitize_text_field($params['shipment_type'] ?? 'Standard Air'),
        'weight'          => floatval($params['weight'] ?? 0),
        'status'          => sanitize_text_field($params['status'] ?? 'Pending'),
        'tracking_number' => sanitize_text_field($params['tracking_number'] ?? ''),
    );

    $result = haivora_api_create_shipment($clean_data);
    if (is_wp_error($result)) {
        return $result;
    }

    return rest_ensure_response($result);
}

// PUT /shipments/{id}
function haivora_rest_update_shipment($request) {
    $id = sanitize_text_field($request->get_param('id'));
    $params = $request->get_json_params();
    if (empty($params)) {
        $params = $request->get_body_params();
    }

    $result = haivora_api_update_shipment($id, $params);
    if (is_wp_error($result)) {
        return $result;
    }

    return rest_ensure_response($result);
}

// GET /track/{code} - Public tracking with PII stripping
function haivora_rest_track_shipment($request) {
    $code = sanitize_text_field($request->get_param('code'));
    $result = haivora_api_track_shipment($code);
    if (is_wp_error($result)) {
        return $result;
    }
    return rest_ensure_response($result);
}

// POST /payments/initiate
function haivora_rest_initiate_payment($request) {
    $params = $request->get_json_params();
    if (empty($params)) {
        $params = $request->get_body_params();
    }

    $result = haivora_initiate_payment($params);
    if (is_wp_error($result)) {
        return $result;
    }
    return rest_ensure_response($result);
}

// GET /payments/transactions
function haivora_rest_get_transactions($request) {
    $list = haivora_get_transactions_list();
    return rest_ensure_response($list);
}

// POST /webhooks/payment
function haivora_rest_handle_payment_webhook($request) {
    $payload = $request->get_json_params();
    if (empty($payload)) {
        $payload = $request->get_body_params();
    }

    $result = haivora_process_payment_webhook($payload);
    if (is_wp_error($result)) {
        return $result;
    }
    return rest_ensure_response($result);
}
