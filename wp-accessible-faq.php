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

// Shortcode to Display FAQs
function faq_accordion_shortcode() {
    ob_start();
    $args = array(
        'post_type' => 'faq',
        'posts_per_page' => -1,
        'order' => 'ASC',
        'orderby' => 'title',
    );
    $query = new WP_Query($args);
    if($query->have_posts()) {
        ?>
        <div class="faq-accordion">
            <?php
            while($query->have_posts()) {
                $query->the_post();
                $faq_id = get_the_ID();
                ?>
                <div class="accordion-item">
                    <button id="button-<?php echo $faq_id; ?>" class="accordion-button" aria-expanded="false" aria-controls="content-<?php echo $faq_id; ?>" tabindex="0">
                        <?php the_title('<h2>', '</h2>'); ?>
                    </button>
                    <div id="content-<?php echo $faq_id; ?>" class="accordion-content" aria-hidden="true" tabindex="0">
                        <?php the_content(); ?>
                    </div>
                </div>
                <?php
            }
            wp_reset_postdata();
            ?>
        </div>
        <?php
    }
    return ob_get_clean();
}
add_shortcode('faq_accordion', 'faq_accordion_shortcode');


