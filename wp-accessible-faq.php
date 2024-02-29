<?php
/**
 * Plugin Name: wp-accessible-faq
 * Description: A plugin to create multiple accessible accordions, ideal for FAQs and more.
 * Version: 2.0 beta 1
 * Author: Techopolis Online Solutions
 */

// Prevent direct file access
if (!defined('ABSPATH')) {
    exit;
}

// Include necessary files
include_once(plugin_dir_path(__FILE__) . 'add-accordion.php');
include_once(plugin_dir_path(__FILE__) . 'manage-accordion.php');
include_once(plugin_dir_path(__FILE__) . 'submenu-options.php');
include_once(plugin_dir_path(__FILE__) . 'manage-questions.php');



// Include auto-updater
require 'kernl-update-checker/kernl-update-checker.php';
$MultiAccordionUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
    'https://kernl.us/api/v1/updates/6531457df13e411d863b0f4f/',
    __FILE__,
    'wp-multi-accordion'
);


