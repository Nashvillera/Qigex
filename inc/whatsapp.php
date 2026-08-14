<?php
/**
 * WhatsApp Integration & Configuration Architecture
 * Phase 6: Quote, Contact & WhatsApp
 *
 * @package Haivora_Logistics
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get Configured WhatsApp Phone Number
 */
function haivora_get_whatsapp_number() {
    $num = get_option('haivora_whatsapp_number', '18005557433');
    return sanitize_text_field($num);
}

/**
 * Get Clean Formatted WhatsApp Number for wa.me link
 */
function haivora_get_clean_whatsapp_number() {
    $raw = haivora_get_whatsapp_number();
    // Remove non-numeric characters except leading plus if present
    $clean = preg_replace('/[^0-9]/', '', $raw);
    return $clean ? $clean : '18005557433';
}

/**
 * Get Configured Default WhatsApp Message
 */
function haivora_get_whatsapp_default_message() {
    $msg = get_option('haivora_whatsapp_message', 'Hello Qidex Logistics, I would like to inquire about freight quotes and cargo tracking services.');
    return sanitize_text_field($msg);
}

/**
 * Generate Direct WhatsApp Chat Link
 */
function haivora_get_whatsapp_link($custom_message = '') {
    $phone = haivora_get_clean_whatsapp_number();
    $message = $custom_message ? $custom_message : haivora_get_whatsapp_default_message();
    return 'https://wa.me/' . $phone . '?text=' . rawurlencode($message);
}

/**
 * Render Floating WhatsApp "Chat With Us" Widget Button
 */
function haivora_render_whatsapp_floating_button() {
    $link = haivora_get_whatsapp_link();
    ?>
    <!-- Floating WhatsApp Widget -->
    <div id="haivora-whatsapp-widget" style="position: fixed; bottom: 24px; right: 24px; z-index: 99999; display: flex; align-items: center; gap: 10px;">
        <div class="wa-tooltip" style="background: #0F172A; color: #FFFFFF; font-size: 0.8rem; font-weight: 700; padding: 6px 12px; border-radius: 6px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); display: flex; align-items: center; gap: 6px; border: 1px solid #1E293B; pointer-events: none; transition: all 0.3s ease;">
            <span style="width: 8px; height: 8px; background: #25D366; border-radius: 50%; display: inline-block;"></span>
            Chat with Logistics Support
        </div>
        <a href="<?php echo esc_url($link); ?>" target="_blank" rel="noopener" aria-label="Chat with Qidex Logistics on WhatsApp" style="width: 56px; height: 56px; background-color: #25D366; color: #FFFFFF; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 6px 20px rgba(37, 211, 102, 0.4); text-decoration: none; transition: transform 0.2s ease, box-shadow 0.2s ease;">
            <svg width="30" height="30" viewBox="0 0 24 24" fill="currentColor"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
        </a>
    </div>
    <style>
        #haivora-whatsapp-widget a:hover {
            transform: scale(1.08);
            box-shadow: 0 8px 25px rgba(37, 211, 102, 0.6);
        }
        @media (max-width: 640px) {
            #haivora-whatsapp-widget .wa-tooltip { display: none; }
            #haivora-whatsapp-widget { bottom: 16px; right: 16px; }
        }
    </style>
    <?php
}
add_action('wp_footer', 'haivora_render_whatsapp_floating_button');

/**
 * WhatsApp API Dispatcher Architecture (Future Cloud API / Twilio Ready)
 */
function haivora_send_whatsapp_api($to_phone, $message_text) {
    // Format recipient phone
    $clean_phone = preg_replace('/[^0-9]/', '', $to_phone);
    if (!$clean_phone) return false;

    // Action Hook for third-party extensions
    do_action('haivora_whatsapp_api_send', $clean_phone, $message_text);

    // Simulated API dispatch log
    $log = get_option('haivora_whatsapp_api_logs', array());
    array_unshift($log, array(
        'id'        => uniqid('wa_'),
        'to'        => $clean_phone,
        'message'   => $message_text,
        'timestamp' => current_time('mysql'),
        'status'    => 'Sent'
    ));
    update_option('haivora_whatsapp_api_logs', array_slice($log, 0, 30));

    return true;
}
