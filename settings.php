<?php

if (!defined('ABSPATH')) {
    exit;
}

include_once('license-checker.php');

// Registering the settings submenu under the FAQ post type
function faq_license_submenu() {
    add_submenu_page(
        'edit.php?post_type=faq',
        'License Settings',
        'License Settings',
        'manage_options',
        'faq-license-settings',
        'faq_license_settings_page_callback'
    );
}
add_action('admin_menu', 'faq_license_submenu');

// The callback function for rendering the settings page
function faq_license_settings_page_callback() {
    $message = '';
    $license_status = 'unknown';

    if (isset($_POST['submit_license'])) {
        // License checking
        $license_key = sanitize_text_field($_POST['license_key']);
        $product_id = "EKeffS79qIwGp5MyHRS6oQ==";
        $verification_result = check_gumroad_license($license_key, $product_id);

        // Handle the result of the verification
        if($verification_result) {
            $message = "License verified successfully!";
            $license_status = 'valid';
        } else {
            $message = "License verification failed. Please check your license key.";
            $license_status = 'invalid';
        }
    }
    
    // Render the settings page
    echo '<div class="wrap">';
    echo '<h1>License Settings for WP Accessible FAQ</h1>';
    if (!empty($message)) {
        echo '<div id="message" class="updated fade"><p>' . $message . '</p></div>';
    }
    echo '<form method="post" action="">';
    echo '<table class="form-table">';
    echo '<tbody>';
    echo '<tr>';
    echo '<th scope="row">License Key</th>';
    echo '<td>';
    echo '<input name="license_key" type="text" value="' . get_option('faq_plugin_license_key') . '" class="regular-text">';
    echo '</td>';
    echo '</tr>';
    echo '</tbody>';
    echo '</table>';
    echo '<p class="submit"><input type="submit" name="submit_license" id="submit" class="button button-primary" value="Save Changes"></p>';
    echo '</form>';
    echo '</div>';
}

// Remember to handle where and how you're storing the license key and status, such as in the WordPress options table.
