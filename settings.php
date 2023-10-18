<?php

if (!defined('ABSPATH')) {
    exit;
}

// Add the jQuery library to the WordPress admin
function enqueue_admin_custom_scripts() {
    wp_enqueue_script('jquery');
}
add_action('admin_enqueue_scripts', 'enqueue_admin_custom_scripts');

function check_gumroad_license($license_key, $product_id) {
    $response = wp_remote_post('https://api.gumroad.com/v2/licenses/verify', array(
        'body' => array(
            'product_id' => $product_id,
            'license_key' => $license_key
        )
    ));

    if (is_wp_error($response)) {
        return array('status' => 'error', 'message' => $response->get_error_message());
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    $result = array();

    if (isset($data['success']) && $data['success'] && !$data['purchase']['refunded']) {
        $result['status'] = 'licensed';
    } else {
        $result['status'] = 'not licensed';
    }

    return $result;
}

// Registering the settings submenu under the FAQ post type
function faq_license_submenu() {
    add_submenu_page(
        'edit.php?post_type=faq',
        'License Settings',
        'Settings',
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
        $license_key = sanitize_text_field($_POST['license_key']);
        $product_id = "EKeffS79qIwGp5MyHRS6oQ==";  // Replacing with the provided product ID
        $verification_result = check_gumroad_license($license_key, $product_id);

        switch ($verification_result['status']) {
            case 'licensed':
                update_option('faq_license_key', $license_key);
                $message = '<div class="updated notice is-dismissible"><p>License key saved and is valid!</p></div>';
                $license_status = 'licensed';
                break;
            case 'not licensed':
                $message = '<div class="error notice is-dismissible"><p>Invalid license key!</p></div>';
                $license_status = 'not licensed';
                break;
            default:
                $message = '<div class="error notice is-dismissible"><p>Error: ' . esc_html($verification_result['message']) . '</p></div>';
                $license_status = 'error';
                break;
        }
    }

    $saved_license_key = get_option('faq_license_key', '');

    ?>
    <div class="wrap">
        <h2>License Settings for FAQ</h2>
        <?php echo $message; ?>
        <form method="post" action="">
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">License Key:</th>
                    <td>
                        <input type="text" name="license_key" value="<?php echo esc_attr($saved_license_key); ?>" class="regular-text" />
                        <?php if ($license_status !== 'unknown'): ?>
                            <span>Status: <strong><?php echo esc_html($license_status); ?></strong></span>
                        <?php endif; ?>
                    </td>
                </tr>
            </table>
            <p class="submit">
                <input type="submit" name="submit_license" class="button-primary" value="Save License Key" />
            </p>
        </form>
    </div>
    <?php
}
