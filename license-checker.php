<?php

// Prevent direct file access
if (!defined('ABSPATH')) {
    exit;
}

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
