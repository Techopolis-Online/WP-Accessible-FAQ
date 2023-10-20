<?php
/**
 * Plugin Name: wp-accessible-faq
 * Description: A plugin to create individual accessible FAQ accordion boxes with enhanced styling.
 * Version: 0.0.4
 * Author: Taylor Arndt
 */

// Prevent direct file access
if (!defined('ABSPATH')) {
    exit;
}

// include the settings files and the license checker
include_once('settings.php');
include_once('license-checker.php');
include_once('faq-cpt.php');
include_once('faq-shortcodes.php');

function my_plugin_enqueue_assets() {
    // Enqueue the CSS
    wp_enqueue_style('my-plugin-style', plugins_url('assets/css/style.css', __FILE__), array(), '1.0.0');

    // Enqueue the JS
    wp_enqueue_script('my-plugin-script', plugins_url('assets/js/script.js', __FILE__), array('jquery'), '1.0.0', true); 
}
add_action('wp_enqueue_scripts', 'my_plugin_enqueue_assets');

// include auto updater
require 'kernl-update-checker/kernl-update-checker.php';
$MyUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
    'https://kernl.us/api/v1/updates/6531457df13e411d863b0f4f/',
    __FILE__,
    'wp-accessible-faq'
);
