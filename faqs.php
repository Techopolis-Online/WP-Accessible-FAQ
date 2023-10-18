<?php
/**
 * Plugin Name: WP Accessible FAQ
 * Description: A plugin to create individual accessible FAQ accordion boxes with enhanced styling.
 * Version: 1.4.0
 * Author: Taylor Arndt
 */

// Include the Composer autoloader
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

// Use the Puc_v4_Factory class
if (class_exists('Puc_v4_Factory')) {
    $updateChecker = Puc_v4_Factory::buildUpdateChecker(
        'https://github.com/tayarndt/WP-Accessible-FAQ',
        __FILE__,
        'WP-Accessible-FAQ'
    );

 include_once('settings.php');
 include_once(plugin_dir_path(__FILE__) . 'Auto Update.php');

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
        <style>
            .faq-accordion {
                width: 100%;
            }
            .accordion-item {
                background-color: #154c79; /* Box Background Color */
                color: #F5F5DC; /* Text Color */
                margin-bottom: 20px; /* Space between boxes */
                border-radius: 8px; /* Rounded Corners */
                overflow: hidden;
            }
            .accordion-content {
                max-height: 0;
                overflow: hidden;
                transition: max-height 0.2s ease-out;
                background-color: #ffffff; /* Content Background Color */
                color: #000000; /* Answer Text Color */
                padding: 0 10px;
                box-sizing: border-box;
            }
            .accordion-button {
                cursor: pointer;
                padding: 10px;
                border: none;
                text-align: left;
                width: 100%;
                box-sizing: border-box;
                background-color: inherit;
                color: inherit;
                font-size: inherit;
                margin: 0;
                display: block;
            }
            .accordion-button h2 {
                margin-bottom: 0;
            }
        </style>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var accordions = document.querySelectorAll('.accordion-button');
                accordions.forEach(function(accordion) {
                    accordion.addEventListener('click', function() {
                        var content = document.getElementById('content-' + accordion.id.split('-')[1]);
                        var expanded = accordion.getAttribute('aria-expanded') === 'true';
                        accordion.setAttribute('aria-expanded', !expanded);
                        content.setAttribute('aria-hidden', expanded);
                        if(expanded){
                            content.style.maxHeight = '0';
                        }else{
                            content.style.maxHeight = content.scrollHeight + "px";
                        }
                    });
                    accordion.addEventListener('keydown', function(event) {
                        if(event.key === "Enter" || event.key === " ") {
                            event.preventDefault();
                            accordion.click();
                        }
                    });
                });
            });
        </script>
        <?php
    }
    return ob_get_clean();
}
add_shortcode('faq_accordion', 'faq_accordion_shortcode');
}