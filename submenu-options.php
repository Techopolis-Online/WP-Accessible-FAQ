<?php

// require files
require_once('add-accordion.php');
require_once('manage-accordion.php');
require_once('add-questions.php');
require_once("accordian-details.php");

// Add main menu
function wp_accessible_faq_add_menu() {
    add_menu_page(
        __('WP Accessible FAQ', 'wp-accessible-faq'),
        __('WP Accessible FAQ', 'wp-accessible-faq'),
        'manage_options',
        'wp-accessible-faq',
        'render_main_page',
        'dashicons-editor-help',
        30
    );
}

function wp_accessible_faq_add_submenus() {
    // Add Accordion
    add_submenu_page(
        'wp-accessible-faq',
        __('Add Accordion', 'wp-accessible-faq'),
        __('Add Accordion', 'wp-accessible-faq'),
        'manage_options', 
        'wp-accessible-faq-add-accordion',
        'render_add_accordion_page'
    );

    // Manage Accordions
    add_submenu_page(
        'wp-accessible-faq',
        __('Manage Accordions', 'wp-accessible-faq'),
        __('Manage Accordions', 'wp-accessible-faq'),
        'manage_options', 
        'wp-accessible-faq-manage-accordions',
        'render_manage_accordions_page'
    );

    // Add Accordian Details (hidden)
    add_submenu_page(
        '', // Parent slug is null to hide the submenu
        __('Accordion Details', 'wp-accessible-faq'),
        __('Accordion Details', 'wp-accessible-faq'),
        'manage_options', 
        'wp-accessible-faq-accordion-details',  // Page slug
        'render_accordion_details_page'  // Function to display the content
    );

    // Add Questions
    add_submenu_page(
        'wp-accessible-faq',
        __('Add Questions', 'wp-accessible-faq'),
        __('Add Questions', 'wp-accessible-faq'),
        'manage_options', 
        'wp-accessible-faq-add-questions',
        'render_add_questions_page'
    );
}

function render_main_page() {
    // Code to render the main page content
}

add_action('admin_menu', 'wp_accessible_faq_add_menu');
add_action ('admin_menu', 'wp_accessible_faq_add_submenus');
