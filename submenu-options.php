<?php

// include files

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
