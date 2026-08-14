<?php
/**
 * Custom Post Type: Shipment & WordPress Admin Management
 *
 * @package Haivora_Logistics
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Register Custom Post Type: Shipment
 */
function haivora_register_shipment_cpt() {
    $labels = array(
        'name'                  => _x('Shipments', 'Post Type General Name', 'haivora-logistics'),
        'singular_name'         => _x('Shipment', 'Post Type Singular Name', 'haivora-logistics'),
        'menu_name'             => __('Shipment Admin', 'haivora-logistics'),
        'name_admin_bar'        => __('Shipment', 'haivora-logistics'),
        'archives'              => __('Shipment Archives', 'haivora-logistics'),
        'attributes'            => __('Shipment Attributes', 'haivora-logistics'),
        'parent_item_colon'     => __('Parent Shipment:', 'haivora-logistics'),
        'all_items'             => __('All Shipments', 'haivora-logistics'),
        'add_new_item'          => __('Add New Shipment', 'haivora-logistics'),
        'add_new'               => __('Add Shipment', 'haivora-logistics'),
        'new_item'              => __('New Shipment', 'haivora-logistics'),
        'edit_item'             => __('Edit Shipment', 'haivora-logistics'),
        'update_item'           => __('Update Shipment', 'haivora-logistics'),
        'view_item'             => __('View Shipment', 'haivora-logistics'),
        'view_items'            => __('View Shipments', 'haivora-logistics'),
        'search_items'          => __('Search Shipments', 'haivora-logistics'),
        'not_found'             => __('No shipments found', 'haivora-logistics'),
        'not_found_in_trash'    => __('No shipments found in Trash', 'haivora-logistics'),
        'featured_image'        => __('Cargo Image', 'haivora-logistics'),
        'set_featured_image'    => __('Set cargo image', 'haivora-logistics'),
        'remove_featured_image' => __('Remove cargo image', 'haivora-logistics'),
        'use_featured_image'    => __('Use as cargo image', 'haivora-logistics'),
        'insert_into_item'      => __('Insert into shipment', 'haivora-logistics'),
        'uploaded_to_this_item' => __('Uploaded to this shipment', 'haivora-logistics'),
        'items_list'            => __('Shipments list', 'haivora-logistics'),
        'items_list_navigation' => __('Shipments list navigation', 'haivora-logistics'),
        'filter_items_list'     => __('Filter shipments list', 'haivora-logistics'),
    );

    $args = array(
        'label'                 => __('Shipment', 'haivora-logistics'),
        'description'           => __('Shipment Tracking and Telemetry Records', 'haivora-logistics'),
        'labels'                => $labels,
        'supports'              => array('title', 'revisions'),
        'taxonomies'            => array(),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-location-alt',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
    );

    register_post_type('shipment', $args);
}
add_action('init', 'haivora_register_shipment_cpt', 0);

/**
 * Register Meta Boxes for Shipment Administration
 */
function haivora_add_shipment_meta_boxes() {
    // 1. Shipment Overview & Telemetry
    add_meta_box(
        'haivora_shipment_details',
        __('📦 Shipment Details & Telemetry', 'haivora-logistics'),
        'haivora_shipment_details_meta_box_callback',
        'shipment',
        'normal',
        'high'
    );

    // 2. Sender & Receiver Info
    add_meta_box(
        'haivora_shipment_contacts',
        __('👥 Sender & Receiver Information', 'haivora-logistics'),
        'haivora_shipment_contacts_meta_box_callback',
        'shipment',
        'normal',
        'high'
    );

    // 3. Package Specifications
    add_meta_box(
        'haivora_shipment_specs',
        __('🏷️ Package Specifications & Cargo Details', 'haivora-logistics'),
        'haivora_shipment_specs_meta_box_callback',
        'shipment',
        'normal',
        'default'
    );

    // 4. Tracking Events Repeater
    add_meta_box(
        'haivora_shipment_events',
        __('⏱️ Tracking Events & Milestones History', 'haivora-logistics'),
        'haivora_shipment_events_meta_box_callback',
        'shipment',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'haivora_add_shipment_meta_boxes');

/**
 * Render Shipment Details Meta Box
 */
function haivora_shipment_details_meta_box_callback($post) {
    wp_nonce_field('haivora_shipment_meta_save', 'haivora_shipment_nonce');

    $tracking_number   = get_post_meta($post->ID, '_tracking_number', true);
    $shipment_status   = get_post_meta($post->ID, '_shipment_status', true);
    $origin            = get_post_meta($post->ID, '_origin', true);
    $destination       = get_post_meta($post->ID, '_destination', true);
    $current_location  = get_post_meta($post->ID, '_current_location', true);
    $shipment_date     = get_post_meta($post->ID, '_shipment_date', true);
    $estimated_delivery= get_post_meta($post->ID, '_estimated_delivery', true);
    $actual_delivery   = get_post_meta($post->ID, '_actual_delivery', true);
    $carrier           = get_post_meta($post->ID, '_carrier', true);
    $service_type      = get_post_meta($post->ID, '_service_type', true);

    if (empty($shipment_status)) $shipment_status = 'In Transit';
    if (empty($carrier)) $carrier = 'Qidex Global Logistics';
    if (empty($service_type)) $service_type = 'Air Freight';
    ?>
    <style>
        .haivora-admin-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 15px; margin-bottom: 10px; }
        .haivora-field-group { display: flex; flex-direction: column; }
        .haivora-field-group label { font-weight: 700; font-size: 13px; margin-bottom: 4px; color: #1e293b; }
        .haivora-field-group input, .haivora-field-group select, .haivora-field-group textarea {
            padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 14px;
        }
        .haivora-required-badge { color: #dc2626; font-weight: bold; }
        .haivora-notice-info { background: #eff6ff; border-left: 4px solid #2563eb; padding: 8px 12px; font-size: 12px; margin-bottom: 12px; border-radius: 2px; color: #1e3a8a; }
    </style>

    <div class="haivora-notice-info">
        <strong>Validation Rule:</strong> Tracking numbers must be unique across all shipments. If left blank, a unique Qidex Waybill code will be auto-generated.
    </div>

    <div class="haivora-admin-grid">
        <div class="haivora-field-group">
            <label for="tracking_number">Tracking Number / Waybill <span class="haivora-required-badge">*</span></label>
            <input type="text" id="tracking_number" name="tracking_number" value="<?php echo esc_attr($tracking_number); ?>" placeholder="e.g. QX-8829-US" required style="font-family: monospace; font-weight: bold; text-transform: uppercase;">
        </div>

        <div class="haivora-field-group">
            <label for="shipment_status">Current Status <span class="haivora-required-badge">*</span></label>
            <select id="shipment_status" name="shipment_status">
                <option value="Pending" <?php selected($shipment_status, 'Pending'); ?>>⏳ Pending</option>
                <option value="In Transit" <?php selected($shipment_status, 'In Transit'); ?>>🚚 In Transit</option>
                <option value="Customs Clearance" <?php selected($shipment_status, 'Customs Clearance'); ?>>🛃 Customs Clearance</option>
                <option value="Out for Delivery" <?php selected($shipment_status, 'Out for Delivery'); ?>>📦 Out for Delivery</option>
                <option value="Delivered" <?php selected($shipment_status, 'Delivered'); ?>>✅ Delivered</option>
                <option value="On Hold" <?php selected($shipment_status, 'On Hold'); ?>>⚠️ On Hold</option>
                <option value="Cancelled" <?php selected($shipment_status, 'Cancelled'); ?>>❌ Cancelled</option>
            </select>
        </div>

        <div class="haivora-field-group">
            <label for="origin">Origin <span class="haivora-required-badge">*</span></label>
            <input type="text" id="origin" name="origin" value="<?php echo esc_attr($origin); ?>" placeholder="e.g. JFK Airport, New York, USA">
        </div>

        <div class="haivora-field-group">
            <label for="destination">Destination <span class="haivora-required-badge">*</span></label>
            <input type="text" id="destination" name="destination" value="<?php echo esc_attr($destination); ?>" placeholder="e.g. Frankfurt Airport, Germany">
        </div>

        <div class="haivora-field-group">
            <label for="current_location">Current Location</label>
            <input type="text" id="current_location" name="current_location" value="<?php echo esc_attr($current_location); ?>" placeholder="e.g. Atlantic Flight QX-702">
        </div>

        <div class="haivora-field-group">
            <label for="carrier">Carrier Name</label>
            <input type="text" id="carrier" name="carrier" value="<?php echo esc_attr($carrier); ?>" placeholder="e.g. Qidex Transatlantic Air Lines">
        </div>

        <div class="haivora-field-group">
            <label for="service_type">Service Type</label>
            <select id="service_type" name="service_type">
                <option value="Air Freight" <?php selected($service_type, 'Air Freight'); ?>>✈️ Air Freight</option>
                <option value="Ocean Cargo" <?php selected($service_type, 'Ocean Cargo'); ?>>🚢 Ocean Cargo</option>
                <option value="Express Courier" <?php selected($service_type, 'Express Courier'); ?>>🚀 Express Courier</option>
                <option value="Road Freight" <?php selected($service_type, 'Road Freight'); ?>>🚛 Road Freight</option>
            </select>
        </div>

        <div class="haivora-field-group">
            <label for="shipment_date">Shipment Date</label>
            <input type="date" id="shipment_date" name="shipment_date" value="<?php echo esc_attr($shipment_date); ?>">
        </div>

        <div class="haivora-field-group">
            <label for="estimated_delivery">Estimated Delivery Date & Time</label>
            <input type="text" id="estimated_delivery" name="estimated_delivery" value="<?php echo esc_attr($estimated_delivery); ?>" placeholder="e.g. Aug 14, 2026 - 16:30 GMT">
        </div>

        <div class="haivora-field-group">
            <label for="actual_delivery">Actual Delivery Date & Time</label>
            <input type="text" id="actual_delivery" name="actual_delivery" value="<?php echo esc_attr($actual_delivery); ?>" placeholder="e.g. Aug 14, 2026 - 15:45 GMT">
        </div>
    </div>
    <?php
}

/**
 * Render Contacts Meta Box
 */
function haivora_shipment_contacts_meta_box_callback($post) {
    $sender   = get_post_meta($post->ID, '_sender', true);
    $receiver = get_post_meta($post->ID, '_receiver', true);
    ?>
    <div class="haivora-admin-grid">
        <div class="haivora-field-group">
            <label for="sender">Sender (Consignor) Information</label>
            <input type="text" id="sender" name="sender" value="<?php echo esc_attr($sender); ?>" placeholder="e.g. Acme Tech Supply Corp, NY">
        </div>

        <div class="haivora-field-group">
            <label for="receiver">Receiver (Consignee) Information</label>
            <input type="text" id="receiver" name="receiver" value="<?php echo esc_attr($receiver); ?>" placeholder="e.g. Global Euro Distribution GmbH, Frankfurt">
        </div>
    </div>
    <?php
}

/**
 * Render Package Specifications Meta Box
 */
function haivora_shipment_specs_meta_box_callback($post) {
    $package_type = get_post_meta($post->ID, '_package_type', true);
    $weight       = get_post_meta($post->ID, '_weight', true);
    $quantity     = get_post_meta($post->ID, '_quantity', true);
    $description  = get_post_meta($post->ID, '_description', true);
    ?>
    <div class="haivora-admin-grid">
        <div class="haivora-field-group">
            <label for="package_type">Package / Cargo Type</label>
            <input type="text" id="package_type" name="package_type" value="<?php echo esc_attr($package_type); ?>" placeholder="e.g. Pallet, Container, Box">
        </div>

        <div class="haivora-field-group">
            <label for="weight">Weight / Dimensions</label>
            <input type="text" id="weight" name="weight" value="<?php echo esc_attr($weight); ?>" placeholder="e.g. 450 kg (992 lbs)">
        </div>

        <div class="haivora-field-group">
            <label for="quantity">Quantity / Items</label>
            <input type="text" id="quantity" name="quantity" value="<?php echo esc_attr($quantity); ?>" placeholder="e.g. 12 Crates">
        </div>
    </div>

    <div class="haivora-field-group" style="margin-top: 10px;">
        <label for="description">Shipment Content Description</label>
        <textarea id="description" name="description" rows="3" placeholder="e.g. High-value electronic components - Temperature controlled (2-8°C)"><?php echo esc_textarea($description); ?></textarea>
    </div>
    <?php
}

/**
 * Render Tracking Events Repeater Meta Box
 */
function haivora_shipment_events_meta_box_callback($post) {
    $events = get_post_meta($post->ID, '_tracking_events', true);
    if (!is_array($events)) {
        $events = array();
    }
    ?>
    <div id="haivora-tracking-events-wrapper">
        <p style="margin-bottom: 12px; color: #475569; font-size: 13px;">
            Manage all tracking milestones for this shipment. Events will be displayed chronologically on the public tracking portal.
        </p>

        <div id="haivora-events-container">
            <?php
            if (!empty($events)) :
                foreach ($events as $index => $event) :
                    $evt_status   = isset($event['status']) ? $event['status'] : '';
                    $evt_location = isset($event['location']) ? $event['location'] : '';
                    $evt_date     = isset($event['date']) ? $event['date'] : '';
                    $evt_time     = isset($event['time']) ? $event['time'] : '';
                    $evt_desc     = isset($event['description']) ? $event['description'] : '';
                    ?>
                    <div class="haivora-event-row" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 14px; margin-bottom: 12px; position: relative;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; border-bottom: 1px solid #cbd5e1; padding-bottom: 8px;">
                            <strong style="color: #0f172a; font-size: 14px;">Event #<span class="event-num"><?php echo $index + 1; ?></span></strong>
                            <div class="event-actions">
                                <button type="button" class="button btn-move-up" title="Move Up">▲</button>
                                <button type="button" class="button btn-move-down" title="Move Down">▼</button>
                                <button type="button" class="button button-link-delete btn-delete-event" style="color: #b91c1c; font-weight: bold; margin-left: 10px;">Remove Event</button>
                            </div>
                        </div>

                        <div class="haivora-admin-grid">
                            <div class="haivora-field-group">
                                <label>Status / Title</label>
                                <input type="text" name="tracking_events[<?php echo $index; ?>][status]" value="<?php echo esc_attr($evt_status); ?>" placeholder="e.g. Export Customs Cleared">
                            </div>
                            <div class="haivora-field-group">
                                <label>Location</label>
                                <input type="text" name="tracking_events[<?php echo $index; ?>][location]" value="<?php echo esc_attr($evt_location); ?>" placeholder="e.g. JFK Airport Terminal 4">
                            </div>
                            <div class="haivora-field-group">
                                <label>Date</label>
                                <input type="date" name="tracking_events[<?php echo $index; ?>][date]" value="<?php echo esc_attr($evt_date); ?>">
                            </div>
                            <div class="haivora-field-group">
                                <label>Time</label>
                                <input type="time" name="tracking_events[<?php echo $index; ?>][time]" value="<?php echo esc_attr($evt_time); ?>">
                            </div>
                        </div>

                        <div class="haivora-field-group" style="margin-top: 8px;">
                            <label>Description / Notes</label>
                            <input type="text" name="tracking_events[<?php echo $index; ?>][description]" value="<?php echo esc_attr($evt_desc); ?>" placeholder="e.g. Documentation verified by customs officer.">
                        </div>
                    </div>
                    <?php
                endforeach;
            endif;
            ?>
        </div>

        <button type="button" id="btn-add-tracking-event" class="button button-secondary" style="margin-top: 10px; font-weight: bold;">
            + Add New Tracking Event
        </button>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('haivora-events-container');
        const btnAdd = document.getElementById('btn-add-tracking-event');

        function reindexEvents() {
            const rows = container.querySelectorAll('.haivora-event-row');
            rows.forEach((row, index) => {
                row.querySelector('.event-num').textContent = index + 1;
                row.querySelectorAll('input').forEach(input => {
                    const nameAttr = input.getAttribute('name');
                    if (nameAttr) {
                        const newName = nameAttr.replace(/tracking_events\[\d+\]/, 'tracking_events[' + index + ']');
                        input.setAttribute('name', newName);
                    }
                });
            });
        }

        if (btnAdd) {
            btnAdd.addEventListener('click', function() {
                const index = container.querySelectorAll('.haivora-event-row').length;
                const rowHtml = `
                    <div class="haivora-event-row" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 14px; margin-bottom: 12px; position: relative;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; border-bottom: 1px solid #cbd5e1; padding-bottom: 8px;">
                            <strong style="color: #0f172a; font-size: 14px;">Event #<span class="event-num">${index + 1}</span></strong>
                            <div class="event-actions">
                                <button type="button" class="button btn-move-up" title="Move Up">▲</button>
                                <button type="button" class="button btn-move-down" title="Move Down">▼</button>
                                <button type="button" class="button button-link-delete btn-delete-event" style="color: #b91c1c; font-weight: bold; margin-left: 10px;">Remove Event</button>
                            </div>
                        </div>

                        <div class="haivora-admin-grid">
                            <div class="haivora-field-group">
                                <label>Status / Title</label>
                                <input type="text" name="tracking_events[${index}][status]" value="" placeholder="e.g. In Transit">
                            </div>
                            <div class="haivora-field-group">
                                <label>Location</label>
                                <input type="text" name="tracking_events[${index}][location]" value="" placeholder="e.g. Frankfurt Airport">
                            </div>
                            <div class="haivora-field-group">
                                <label>Date</label>
                                <input type="date" name="tracking_events[${index}][date]" value="">
                            </div>
                            <div class="haivora-field-group">
                                <label>Time</label>
                                <input type="time" name="tracking_events[${index}][time]" value="">
                            </div>
                        </div>

                        <div class="haivora-field-group" style="margin-top: 8px;">
                            <label>Description / Notes</label>
                            <input type="text" name="tracking_events[${index}][description]" value="" placeholder="e.g. In transit to destination hub">
                        </div>
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', rowHtml);
            });
        }

        container.addEventListener('click', function(e) {
            if (e.target.classList.contains('btn-delete-event')) {
                e.target.closest('.haivora-event-row').remove();
                reindexEvents();
            } else if (e.target.classList.contains('btn-move-up')) {
                const row = e.target.closest('.haivora-event-row');
                if (row.previousElementSibling) {
                    row.parentNode.insertBefore(row, row.previousElementSibling);
                    reindexEvents();
                }
            } else if (e.target.classList.contains('btn-move-down')) {
                const row = e.target.closest('.haivora-event-row');
                if (row.nextElementSibling) {
                    row.parentNode.insertBefore(row.nextElementSibling, row);
                    reindexEvents();
                }
            }
        });
    });
    </script>
    <?php
}

/**
 * Save Meta Box Data with Validation, Sanitization, Nonces, and Capability Checks
 */
function haivora_save_shipment_meta_data($post_id) {
    // Nonce Check
    if (!isset($_POST['haivora_shipment_nonce']) || !wp_verify_nonce($_POST['haivora_shipment_nonce'], 'haivora_shipment_meta_save')) {
        return;
    }

    // Autosave check
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Permission Check
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Verify post type
    if (isset($_POST['post_type']) && 'shipment' !== $_POST['post_type']) {
        return;
    }

    // Sanitize and Validate Tracking Number (REQUIRED & UNIQUE)
    $tracking_number = isset($_POST['tracking_number']) ? sanitize_text_field(strtoupper(trim($_POST['tracking_number']))) : '';
    
    if (empty($tracking_number)) {
        // Auto-generate if empty
        $tracking_number = 'QX-' . rand(1000, 9999) . '-US';
    } else {
        // Check Uniqueness
        $existing = get_posts(array(
            'post_type'      => 'shipment',
            'posts_per_page' => 1,
            'post__not_in'   => array($post_id),
            'meta_key'       => '_tracking_number',
            'meta_value'     => $tracking_number,
            'post_status'    => 'any',
        ));

        if (!empty($existing)) {
            // Append unique suffix if duplicate
            $tracking_number = $tracking_number . '-' . rand(10, 99);
            set_transient('haivora_shipment_error_' . $post_id, 'Duplicate tracking number detected. A unique suffix was appended to avoid conflicts: ' . $tracking_number, 45);
        }
    }

    update_post_meta($post_id, '_tracking_number', $tracking_number);

    // Save Other Single Meta Fields
    $fields = array(
        'shipment_status'    => 'sanitize_text_field',
        'origin'             => 'sanitize_text_field',
        'destination'        => 'sanitize_text_field',
        'current_location'   => 'sanitize_text_field',
        'sender'             => 'sanitize_text_field',
        'receiver'           => 'sanitize_text_field',
        'shipment_date'      => 'sanitize_text_field',
        'estimated_delivery' => 'sanitize_text_field',
        'actual_delivery'    => 'sanitize_text_field',
        'carrier'            => 'sanitize_text_field',
        'service_type'       => 'sanitize_text_field',
        'package_type'       => 'sanitize_text_field',
        'weight'             => 'sanitize_text_field',
        'quantity'           => 'sanitize_text_field',
        'description'        => 'sanitize_textarea_field',
    );

    foreach ($fields as $field_key => $sanitize_func) {
        if (isset($_POST[$field_key])) {
            $val = call_user_func($sanitize_func, $_POST[$field_key]);
            update_post_meta($post_id, '_' . $field_key, $val);
        }
    }

    // Save Tracking Events Array
    if (isset($_POST['tracking_events']) && is_array($_POST['tracking_events'])) {
        $sanitized_events = array();
        foreach ($_POST['tracking_events'] as $evt) {
            if (!empty($evt['status']) || !empty($evt['location'])) {
                $sanitized_events[] = array(
                    'status'      => sanitize_text_field($evt['status']),
                    'location'    => sanitize_text_field($evt['location']),
                    'date'        => sanitize_text_field($evt['date']),
                    'time'        => sanitize_text_field($evt['time']),
                    'description' => sanitize_text_field($evt['description']),
                );
            }
        }
        update_post_meta($post_id, '_tracking_events', $sanitized_events);
    } else {
        update_post_meta($post_id, '_tracking_events', array());
    }
}
add_action('save_post_shipment', 'haivora_save_shipment_meta_data');

/**
 * Display Admin Notice if duplicate tracking number was handled
 */
function haivora_shipment_admin_notices() {
    global $post;
    if ($post && 'shipment' === $post->post_type) {
        $error = get_transient('haivora_shipment_error_' . $post->ID);
        if ($error) {
            delete_transient('haivora_shipment_error_' . $post->ID);
            echo '<div class="notice notice-warning is-dismissible"><p><strong>Warning:</strong> ' . esc_html($error) . '</p></div>';
        }
    }
}
add_action('admin_notices', 'haivora_shipment_admin_notices');

/**
 * Customize Shipment Admin Columns
 */
function haivora_shipment_admin_columns($columns) {
    $new_columns = array(
        'cb'               => $columns['cb'],
        'title'            => __('Tracking #', 'haivora-logistics'),
        'shipment_status'  => __('Status', 'haivora-logistics'),
        'route'            => __('Origin → Destination', 'haivora-logistics'),
        'current_location' => __('Current Location', 'haivora-logistics'),
        'contacts'         => __('Sender / Receiver', 'haivora-logistics'),
        'estimated'        => __('Est. Delivery', 'haivora-logistics'),
        'date'             => __('Created', 'haivora-logistics'),
    );
    return $new_columns;
}
add_filter('manage_shipment_posts_columns', 'haivora_shipment_admin_columns');

/**
 * Render Custom Admin Column Content
 */
function haivora_shipment_admin_custom_column($column, $post_id) {
    switch ($column) {
        case 'shipment_status':
            $status = get_post_meta($post_id, '_shipment_status', true);
            $bg_color = '#e2e8f0';
            $text_color = '#334155';

            if ($status === 'In Transit') { $bg_color = '#dbeafe'; $text_color = '#1d4ed8'; }
            elseif ($status === 'Delivered') { $bg_color = '#d1fae5'; $text_color = '#047857'; }
            elseif ($status === 'Customs Clearance') { $bg_color = '#fef3c7'; $text_color = '#b45309'; }
            elseif ($status === 'Pending') { $bg_color = '#f3f4f6'; $text_color = '#4b5563'; }
            elseif ($status === 'On Hold') { $bg_color = '#fee2e2'; $text_color = '#b91c1c'; }

            echo '<span style="display:inline-block; padding: 4px 10px; border-radius: 9999px; font-size: 11px; font-weight: 800; background:' . esc_attr($bg_color) . '; color:' . esc_attr($text_color) . ';">' . esc_html(strtoupper($status)) . '</span>';
            break;

        case 'route':
            $origin = get_post_meta($post_id, '_origin', true);
            $dest   = get_post_meta($post_id, '_destination', true);
            echo '<strong>' . esc_html($origin ? $origin : 'N/A') . '</strong><br><span style="color:#64748b; font-size:11px;">➔ ' . esc_html($dest ? $dest : 'N/A') . '</span>';
            break;

        case 'current_location':
            $loc = get_post_meta($post_id, '_current_location', true);
            echo esc_html($loc ? $loc : '—');
            break;

        case 'contacts':
            $sender   = get_post_meta($post_id, '_sender', true);
            $receiver = get_post_meta($post_id, '_receiver', true);
            echo '<strong>From:</strong> ' . esc_html($sender ? $sender : 'N/A') . '<br><span style="color:#64748b; font-size:11px;"><strong>To:</strong> ' . esc_html($receiver ? $receiver : 'N/A') . '</span>';
            break;

        case 'estimated':
            $est = get_post_meta($post_id, '_estimated_delivery', true);
            echo esc_html($est ? $est : '—');
            break;
    }
}
add_action('manage_shipment_posts_custom_column', 'haivora_shipment_admin_custom_column', 10, 2);

/**
 * Filter Shipments in Admin by Status
 */
function haivora_shipment_admin_filter_dropdown() {
    global $typenow;
    if ($typenow === 'shipment') {
        $current_status = isset($_GET['filter_shipment_status']) ? sanitize_text_field($_GET['filter_shipment_status']) : '';
        $statuses = array('Pending', 'In Transit', 'Customs Clearance', 'Out for Delivery', 'Delivered', 'On Hold', 'Cancelled');
        ?>
        <select name="filter_shipment_status">
            <option value=""><?php _e('All Statuses', 'haivora-logistics'); ?></option>
            <?php foreach ($statuses as $st) : ?>
                <option value="<?php echo esc_attr($st); ?>" <?php selected($current_status, $st); ?>><?php echo esc_html($st); ?></option>
            <?php endforeach; ?>
        </select>
        <?php
    }
}
add_action('restrict_manage_posts', 'haivora_shipment_admin_filter_dropdown');

/**
 * Apply Admin Status Filter to Query
 */
function haivora_shipment_admin_filter_query($query) {
    global $pagenow, $typenow;
    if (is_admin() && $pagenow === 'edit.php' && $typenow === 'shipment') {
        if (!empty($_GET['filter_shipment_status'])) {
            $query->set('meta_key', '_shipment_status');
            $query->set('meta_value', sanitize_text_field($_GET['filter_shipment_status']));
        }
    }
}
add_action('parse_query', 'haivora_shipment_admin_filter_query');
