<?php

// include files
include_once('add-accordion.php');
include_once('manage-accordion.php');
include_once('add-questions.php');
include_once('manage-questions.php');
include_once('accordian-details.php');


// Add submenus
function wp_accessible_faq_add_submenus() {
    
    // Add Accordion
    add_submenu_page(
        'options-general.php',
        __('Add Accordion', 'wp-accessible-faq'),
        __('Add Accordion', 'wp-accessible-faq'),
        'manage_options', 
        'wp-accessible-faq-add-accordion',
        'render_add_accordion_page'
    );

    // Manage Accordions
    add_submenu_page(
        'options-general.php',
        __('Manage Accordions', 'wp-accessible-faq'),
        __('Manage Accordions', 'wp-accessible-faq'),
        'manage_options', 
        'wp-accessible-faq-manage-accordions',
        'render_manage_accordions_page'
    );

    // add accordian details submenu that is hidden
    add_submenu_page(
        '',  // Parent slug is null to hide the submenu
        __('Accordion Details', 'wp-accessible-faq'),
        __('Accordion Details', 'wp-accessible-faq'),
        'manage_options', 
        'wp-accessible-faq-accordion-details',  // Page slug
        'render_accordion_details_page'  // Function to display the content
    );

    // Add Questions
    add_submenu_page(
        'options-general.php',
        __('Add Questions', 'wp-accessible-faq'),
        __('Add Questions', 'wp-accessible-faq'),
        'manage_options', 
        'wp-accessible-faq-add-questions',
        'render_add_questions_page'
    );

    // Manage Questions
    add_submenu_page(
        'options-general.php',
        __('Manage Questions', 'wp-accessible-faq'),
        __('Manage Questions', 'wp-accessible-faq'),
        'manage_options', 
        'wp-accessible-faq-manage-questions',
        'render_manage_questions_page'  // <- This is where the missing parenthesis was.
    );
}

add_action('admin_menu', 'wp_accessible_faq_add_submenus');
