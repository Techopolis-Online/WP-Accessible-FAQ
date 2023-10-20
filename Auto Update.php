<?php
// Ensure the file is being accessed within a WordPress context.
if (!defined('ABSPATH')) {
    exit;
}

require 'kernl-update-checker/kernl-update-checker.php';

class WP_Accessible_FAQ_Updater {
    public function __construct() {
        add_action('admin_init', [$this, 'reset_update_state']); // Reset update state every time an admin page loads.
        add_action('plugins_loaded', [$this, 'initialize_updater']);
        add_action('admin_notices', [$this, 'display_debug_notices']);
    }

    public function reset_update_state() {
        delete_site_transient('update_plugins');
        echo '<div class="notice notice-warning"><p>Update state reset!</p></div>';
    }

    public function initialize_updater() {
        if (!class_exists('Puc_v4_Factory')) {
            echo '<div class="notice notice-error"><p>Puc_v4_Factory class not found. Kernl Update Checker library might be incomplete or modified.</p></div>';
            return;
        }

        $MyUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
            'https://kernl.us/api/v1/updates/653138c378017430c6c3d7e8/',
            __FILE__,
            'wp-accessible-faq'
        );

        $MyUpdateChecker->debugMode = true;
    }

    public function display_debug_notices() {
        $update = get_site_transient('update_plugins');
        
        if ($update) {
            echo '<div class="notice notice-info"><p>Update transient exists. Checking for our plugin...</p></div>';
            
            if (isset($update->response[plugin_basename(__FILE__)])) {
                $msg = $update->response[plugin_basename(__FILE__)]->upgrade_notice ?? 'There is an update available.';
                echo '<div class="notice notice-success"><p>Plugin Update Notice: ' . esc_html($msg) . '</p></div>';
            } else {
                echo '<div class="notice notice-warning"><p>Our plugin not found in the update transient.</p></div>';
            }
        } else {
            echo '<div class="notice notice-error"><p>Update transient does not exist.</p></div>';
        }
    }
}

// Instantiate the updater class.
new WP_Accessible_FAQ_Updater();
