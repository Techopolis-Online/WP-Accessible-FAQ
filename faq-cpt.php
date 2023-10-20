<?php
/**
 * File Name: faq-cpt.php
 * Description: Registers the FAQ Custom Post Type.
 */

// Prevent direct file access
if (!defined('ABSPATH')) {
    exit;
}

// Register Custom Post Type
function create_faq_cpt() {
    $labels = array(
        'name' => 'FAQs',
        'singular_name' => 'FAQ',
    );
    $args = array(
        'label' => 'FAQ',
        'labels' => $labels,
        'supports' => array('title', 'editor', ),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        'show_in_admin_bar' => true,
        'show_in_nav_menus' => true,
        'can_export' => true,
        'has_archive' => true,
        'hierarchical' => false,
        'exclude_from_search' => false,
        'show_in_rest' => true,
        'publicly_queryable' => true,
        'capability_type' => 'post',
    );
    register_post_type('faq', $args);
}
add_action('init', 'create_faq_cpt', 0);
