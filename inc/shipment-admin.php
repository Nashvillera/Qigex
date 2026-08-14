<?php
/**
 * Shipment Administration Helpers and Core Utilities
 *
 * @package Haivora_Logistics
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Fetch shipment object by tracking number / waybill
 *
 * @param string $tracking_number
 * @return array|false
 */
function haivora_get_shipment_by_tracking_number($tracking_number) {
    $clean_code = strtoupper(trim(sanitize_text_field($tracking_number)));
    if (empty($clean_code)) {
        return false;
    }

    $args = array(
        'post_type'      => 'shipment',
        'posts_per_page' => 1,
        'meta_key'       => '_tracking_number',
        'meta_value'     => $clean_code,
        'post_status'    => 'publish',
    );

    $posts = get_posts($args);

    if (empty($posts)) {
        return false;
    }

    $post = $posts[0];
    return haivora_format_shipment_data($post->ID);
}

/**
 * Format full shipment data array from Post ID
 *
 * @param int $post_id
 * @return array
 */
function haivora_format_shipment_data($post_id) {
    $events = get_post_meta($post_id, '_tracking_events', true);
    if (!is_array($events)) {
        $events = array();
    }

    return array(
        'id'                 => $post_id,
        'tracking_number'   => get_post_meta($post_id, '_tracking_number', true),
        'status'             => get_post_meta($post_id, '_shipment_status', true),
        'origin'             => get_post_meta($post_id, '_origin', true),
        'destination'        => get_post_meta($post_id, '_destination', true),
        'current_location'   => get_post_meta($post_id, '_current_location', true),
        'sender'             => get_post_meta($post_id, '_sender', true),
        'receiver'           => get_post_meta($post_id, '_receiver', true),
        'shipment_date'      => get_post_meta($post_id, '_shipment_date', true),
        'estimated_delivery' => get_post_meta($post_id, '_estimated_delivery', true),
        'actual_delivery'    => get_post_meta($post_id, '_actual_delivery', true),
        'carrier'            => get_post_meta($post_id, '_carrier', true),
        'service_type'       => get_post_meta($post_id, '_service_type', true),
        'package_type'       => get_post_meta($post_id, '_package_type', true),
        'weight'             => get_post_meta($post_id, '_weight', true),
        'quantity'           => get_post_meta($post_id, '_quantity', true),
        'description'        => get_post_meta($post_id, '_description', true),
        'events'             => $events,
        'last_updated'       => get_the_modified_date('Y-m-d H:i:s', $post_id),
    );
}

/**
 * Seed initial sample shipments if database has no shipments registered
 */
function haivora_seed_default_shipments() {
    $existing = get_posts(array(
        'post_type'      => 'shipment',
        'posts_per_page' => 1,
        'post_status'    => 'any',
    ));

    if (!empty($existing)) {
        return; // Already populated
    }

    $samples = array(
        array(
            'tracking_number'   => 'QX-8829-US',
            'status'             => 'In Transit',
            'origin'             => 'JFK, New York, USA',
            'destination'        => 'FRA, Frankfurt, Germany',
            'current_location'   => 'Flight QX-702 (Mid-Atlantic)',
            'sender'             => 'Acme Tech Supply Corp, NY',
            'receiver'           => 'Global Euro Distribution GmbH, Frankfurt',
            'shipment_date'      => '2026-08-12',
            'estimated_delivery' => 'Aug 14, 2026 - 16:30 GMT',
            'actual_delivery'    => 'Pending',
            'carrier'            => 'Qidex Transatlantic Air Freight',
            'service_type'       => 'Air Freight',
            'package_type'       => 'Temperature Controlled Pallets',
            'weight'             => '450 kg',
            'quantity'           => '12 Crates',
            'description'        => 'High-value semiconductor electronic components.',
            'events'             => array(
                array(
                    'status'      => '1. Waybill & Cargo Registered',
                    'location'    => 'JFK Logistics Hub, New York',
                    'date'        => '2026-08-12',
                    'time'        => '08:30 AM',
                    'description' => 'Parcel accepted and waybill created at dispatch hub.'
                ),
                array(
                    'status'      => '2. Export Customs Cleared',
                    'location'    => 'US Customs & Border Protection',
                    'date'        => '2026-08-13',
                    'time'        => '14:15 PM',
                    'description' => 'Manifest approved and cleared for export.'
                ),
                array(
                    'status'      => '3. Transatlantic Airborne Transit',
                    'location'    => 'Onboard Flight QX-702',
                    'date'        => '2026-08-14',
                    'time'        => '02:00 AM',
                    'description' => 'Aircraft en route over Mid-Atlantic corridor.'
                ),
                array(
                    'status'      => '4. Out for Final Terminal Delivery',
                    'location'    => 'Frankfurt Cargo City South',
                    'date'        => '2026-08-14',
                    'time'        => '16:30 PM',
                    'description' => 'Scheduled for arrival at Frankfurt distribution center.'
                )
            )
        ),
        array(
            'tracking_number'   => 'QX-9912-DE',
            'status'             => 'Delivered',
            'origin'             => 'HAM, Hamburg Port, Germany',
            'destination'        => 'SIN, Port of Singapore',
            'current_location'   => 'Consignee Warehouse Hub #3',
            'sender'             => 'Hamburg Industrial Machinery AG',
            'receiver'           => 'Singapore Maritime Logistics Pte',
            'shipment_date'      => '2026-08-01',
            'estimated_delivery' => 'Aug 13, 2026 - 15:45 SGT',
            'actual_delivery'    => 'Aug 13, 2026 - 15:45 SGT',
            'carrier'            => 'Qidex Ocean Line Voyager',
            'service_type'       => 'Ocean Cargo',
            'package_type'       => 'FCL High Cube Container',
            'weight'             => '18,400 kg',
            'quantity'           => '2 Containers (40ft)',
            'description'        => 'Heavy industrial machinery assemblies and spares.',
            'events'             => array(
                array(
                    'status'      => '1. Container Loaded',
                    'location'    => 'Port of Hamburg',
                    'date'        => '2026-08-01',
                    'time'        => '10:00 AM',
                    'description' => 'Container sealed and loaded onto vessel.'
                ),
                array(
                    'status'      => '2. Suez Passage Transit',
                    'location'    => 'Suez Canal Maritime Corridor',
                    'date'        => '2026-08-05',
                    'time'        => '11:30 AM',
                    'description' => 'Maritime passage approved.'
                ),
                array(
                    'status'      => '3. Arrived at Destination Hub',
                    'location'    => 'Pasir Panjang Terminal, Singapore',
                    'date'        => '2026-08-12',
                    'time'        => '09:15 AM',
                    'description' => 'Discharged from vessel and cleared import customs.'
                ),
                array(
                    'status'      => '4. Delivered & Signed',
                    'location'    => 'Consignee Warehouse Hub #3',
                    'date'        => '2026-08-13',
                    'time'        => '15:45 PM',
                    'description' => 'Signed by Consignee (electronic POD confirmed).'
                )
            )
        ),
        array(
            'tracking_number'   => 'QX-3301-CN',
            'status'             => 'Customs Clearance',
            'origin'             => 'PVG, Shanghai Pudong, China',
            'destination'        => 'LHR, London Heathrow, UK',
            'current_location'   => 'HMRC Customs Clearance Bay, Heathrow',
            'sender'             => 'Shanghai Precision Electronics',
            'receiver'           => 'UK Express Retail Logistics Ltd',
            'shipment_date'      => '2026-08-12',
            'estimated_delivery' => 'Aug 15, 2026 - 12:00 GMT',
            'actual_delivery'    => 'Pending Customs Clearance',
            'carrier'            => 'Qidex Global Courier Express',
            'service_type'       => 'Express Courier',
            'package_type'       => 'Cardboard Cartons',
            'weight'             => '85 kg',
            'quantity'           => '5 Boxes',
            'description'        => 'Consumer electronics and wearables.',
            'events'             => array(
                array(
                    'status'      => '1. Parcel Picked Up',
                    'location'    => 'Shanghai Warehouse #9',
                    'date'        => '2026-08-12',
                    'time'        => '18:00 PM',
                    'description' => 'Collected by driver and processed at hub.'
                ),
                array(
                    'status'      => '2. Import Customs Review',
                    'location'    => 'London Heathrow Freight Center',
                    'date'        => '2026-08-14',
                    'time'        => '08:00 AM',
                    'description' => 'Documentation undergoing HMRC review.'
                )
            )
        )
    );

    foreach ($samples as $sample) {
        $post_id = wp_insert_post(array(
            'post_title'   => $sample['tracking_number'],
            'post_type'    => 'shipment',
            'post_status'  => 'publish',
            'post_content' => $sample['description'],
        ));

        if ($post_id && !is_wp_error($post_id)) {
            update_post_meta($post_id, '_tracking_number', $sample['tracking_number']);
            update_post_meta($post_id, '_shipment_status', $sample['status']);
            update_post_meta($post_id, '_origin', $sample['origin']);
            update_post_meta($post_id, '_destination', $sample['destination']);
            update_post_meta($post_id, '_current_location', $sample['current_location']);
            update_post_meta($post_id, '_sender', $sample['sender']);
            update_post_meta($post_id, '_receiver', $sample['receiver']);
            update_post_meta($post_id, '_shipment_date', $sample['shipment_date']);
            update_post_meta($post_id, '_estimated_delivery', $sample['estimated_delivery']);
            update_post_meta($post_id, '_actual_delivery', $sample['actual_delivery']);
            update_post_meta($post_id, '_carrier', $sample['carrier']);
            update_post_meta($post_id, '_service_type', $sample['service_type']);
            update_post_meta($post_id, '_package_type', $sample['package_type']);
            update_post_meta($post_id, '_weight', $sample['weight']);
            update_post_meta($post_id, '_quantity', $sample['quantity']);
            update_post_meta($post_id, '_description', $sample['description']);
            update_post_meta($post_id, '_tracking_events', $sample['events']);
        }
    }
}
add_action('admin_init', 'haivora_seed_default_shipments');
