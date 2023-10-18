<?php

// Include the required library for the plugin update checker.
require_once( plugin_dir_path(__FILE__) . 'vendor/yahnis-elsts/plugin-update-checker/plugin-update-checker.php' );

/**
 * Set up the update checker for the plugin.
 */
function setup_github_plugin_updater() {
    // Configure the plugin update checker.
    $updateChecker = Puc_v4_Factory::buildUpdateChecker(
        'https://github.com/tayarndt/WP-Accessible-FAQ', // GitHub repo URL
        __FILE__, // Full path to the main plugin file
        'WP-Accessible-FAQ' // Slug of the plugin
    );
    
    // Optional: If you're using a private repository, set the access token like this:
    // $updateChecker->setAuthentication('your-token-here');

    // Optional: Set the branch that contains the stable release.
    $updateChecker->setBranch('master'); // Updated to use the master branch
}

// Hook the function to the init action to set up the update checker after all plugins are loaded.
add_action('init', 'setup_github_plugin_updater');

