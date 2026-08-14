<?php
/**
 * Customer Authentication & Role Management Module
 * Phase 5: Customer Accounts and Dashboard
 *
 * @package Haivora_Logistics
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register 'customer' role if it doesn't already exist
 */
function haivora_register_customer_role() {
    if (!get_role('customer')) {
        add_role('customer', __('Customer', 'haivora-logistics'), array(
            'read' => true,
            'edit_posts' => false,
            'delete_posts' => false,
        ));
    }
}
add_action('init', 'haivora_register_customer_role');

/**
 * Customer Registration Handler using WordPress authentication APIs
 */
function haivora_register_customer_user($data) {
    $first_name       = isset($data['first_name']) ? sanitize_text_field($data['first_name']) : '';
    $last_name        = isset($data['last_name']) ? sanitize_text_field($data['last_name']) : '';
    $email            = isset($data['email']) ? sanitize_email($data['email']) : '';
    $phone            = isset($data['phone']) ? sanitize_text_field($data['phone']) : '';
    $company          = isset($data['company']) ? sanitize_text_field($data['company']) : '';
    $account_type     = isset($data['account_type']) ? sanitize_text_field($data['account_type']) : 'corporate';
    $password         = isset($data['password']) ? $data['password'] : '';
    $confirm_password = isset($data['confirm_password']) ? $data['confirm_password'] : '';

    // Field Validation
    if (empty($first_name) || empty($last_name)) {
        return new WP_Error('missing_name', __('First Name and Last Name are required.', 'haivora-logistics'));
    }

    if (empty($email) || !is_email($email)) {
        return new WP_Error('invalid_email', __('Please provide a valid business email address.', 'haivora-logistics'));
    }

    if (empty($phone)) {
        return new WP_Error('missing_phone', __('Phone number is required for shipment delivery alerts.', 'haivora-logistics'));
    }

    if (empty($password)) {
        return new WP_Error('missing_password', __('Password is required.', 'haivora-logistics'));
    }

    if (strlen($password) < 6) {
        return new WP_Error('weak_password', __('Password must be at least 6 characters in length.', 'haivora-logistics'));
    }

    if ($password !== $confirm_password) {
        return new WP_Error('password_mismatch', __('Password and Confirm Password do not match.', 'haivora-logistics'));
    }

    // Duplicate account prevention
    if (email_exists($email) || username_exists($email)) {
        return new WP_Error('duplicate_email', __('An account with this email address already exists. Please log in.', 'haivora-logistics'));
    }

    // Create User with wp_insert_user
    $user_data = array(
        'user_login'   => $email,
        'user_email'   => $email,
        'user_pass'    => $password,
        'first_name'   => $first_name,
        'last_name'    => $last_name,
        'display_name' => trim($first_name . ' ' . $last_name),
        'role'         => 'customer',
    );

    $user_id = wp_insert_user($user_data);

    if (is_wp_error($user_id)) {
        return $user_id;
    }

    // Store custom metadata
    update_user_meta($user_id, 'phone_number', $phone);
    update_user_meta($user_id, 'company_name', $company);
    update_user_meta($user_id, 'account_type', $account_type);
    
    // Default notification preferences
    update_user_meta($user_id, 'notify_email', '1');
    update_user_meta($user_id, 'notify_whatsapp', '1');
    update_user_meta($user_id, 'notify_sms', '0');

    return $user_id;
}

/**
 * Customer Login Handler using wp_signon()
 */
function haivora_login_customer_user($credentials) {
    $login_input = isset($credentials['log']) ? sanitize_text_field($credentials['log']) : '';
    $password    = isset($credentials['pwd']) ? $credentials['pwd'] : '';
    $remember    = !empty($credentials['rememberme']);

    if (empty($login_input) || empty($password)) {
        return new WP_Error('missing_credentials', __('Please enter both your email/username and password.', 'haivora-logistics'));
    }

    // Support logging in via Email or Username
    if (is_email($login_input)) {
        $user = get_user_by('email', $login_input);
        if ($user) {
            $username = $user->user_login;
        } else {
            return new WP_Error('invalid_login', __('Invalid email or password.', 'haivora-logistics'));
        }
    } else {
        $username = $login_input;
    }

    $creds = array(
        'user_login'    => $username,
        'user_password' => $password,
        'remember'      => $remember,
    );

    $user = wp_signon($creds, false);

    if (is_wp_error($user)) {
        return new WP_Error('invalid_login', __('Invalid login credentials. Please check your details and try again.', 'haivora-logistics'));
    }

    return $user;
}

/**
 * Update Customer Profile
 * Ensures customers CANNOT modify privileged WordPress roles
 */
function haivora_update_customer_profile_data($user_id, $data) {
    // Permission check
    if (!current_user_can('edit_user', $user_id) && get_current_user_id() !== $user_id) {
        return new WP_Error('unauthorized', __('Unauthorized profile modification attempt.', 'haivora-logistics'));
    }

    $first_name  = isset($data['first_name']) ? sanitize_text_field($data['first_name']) : '';
    $last_name   = isset($data['last_name']) ? sanitize_text_field($data['last_name']) : '';
    $phone       = isset($data['phone']) ? sanitize_text_field($data['phone']) : '';
    $company     = isset($data['company']) ? sanitize_text_field($data['company']) : '';

    $user_args = array(
        'ID'         => $user_id,
        'first_name' => $first_name,
        'last_name'  => $last_name,
        'display_name' => trim($first_name . ' ' . $last_name),
    );

    // Password Update if requested
    if (!empty($data['new_password'])) {
        $current_password = isset($data['current_password']) ? $data['current_password'] : '';
        $new_password     = $data['new_password'];
        $confirm_password = isset($data['confirm_new_password']) ? $data['confirm_new_password'] : '';

        $user_obj = get_userdata($user_id);
        if (!$user_obj || !wp_check_password($current_password, $user_obj->user_pass, $user_id)) {
            return new WP_Error('wrong_current_pass', __('Current password is incorrect.', 'haivora-logistics'));
        }

        if (strlen($new_password) < 6) {
            return new WP_Error('weak_new_pass', __('New password must be at least 6 characters.', 'haivora-logistics'));
        }

        if ($new_password !== $confirm_password) {
            return new WP_Error('mismatch_new_pass', __('New password and confirmation do not match.', 'haivora-logistics'));
        }

        $user_args['user_pass'] = $new_password;
    }

    // STRICT ROLE PROTECTION: Strip any role manipulation attempt
    unset($user_args['role']);
    unset($user_args['roles']);

    $updated_id = wp_update_user($user_args);

    if (is_wp_error($updated_id)) {
        return $updated_id;
    }

    update_user_meta($user_id, 'phone_number', $phone);
    update_user_meta($user_id, 'company_name', $company);

    return true;
}

/**
 * Access Control Helper: Verify whether customer can access specific shipment
 */
function haivora_customer_can_view_shipment($user_id, $shipment_data) {
    if (!$user_id) return false;

    $user = get_userdata($user_id);
    if (!$user) return false;

    // Administrators can access any shipment
    if (in_array('administrator', (array) $user->roles, true) || user_can($user, 'manage_options')) {
        return true;
    }

    $user_email = strtolower(trim($user->user_email));

    // Check if shipment matches customer's email, sender, receiver, or customer_id
    $s_email  = isset($shipment_data['customer_email']) ? strtolower(trim($shipment_data['customer_email'])) : '';
    $sender   = isset($shipment_data['sender']) ? strtolower(trim($shipment_data['sender'])) : '';
    $receiver = isset($shipment_data['receiver']) ? strtolower(trim($shipment_data['receiver'])) : '';
    $cust_id  = isset($shipment_data['customer_id']) ? (int)$shipment_data['customer_id'] : 0;

    if ($cust_id === $user_id) return true;
    if (!empty($s_email) && $s_email === $user_email) return true;
    if (!empty($sender) && strpos($sender, $user_email) !== false) return true;
    if (!empty($receiver) && strpos($receiver, $user_email) !== false) return true;

    // Check name match
    $full_name = strtolower(trim($user->first_name . ' ' . $user->last_name));
    if (!empty($full_name) && (strpos($sender, $full_name) !== false || strpos($receiver, $full_name) !== false)) {
        return true;
    }

    return false;
}
