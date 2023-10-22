<?php

// Enqueue the CSS and JS assets
function wp_multi_accordion_enqueue_assets() {
    // Enqueue the CSS
    wp_enqueue_style('wp-           multi-accordion-style', plugins_url('/assets/css/style.css', __FILE__), [], '1.1.0');    
    // Enqueue the JS from the 'js' directory
    wp_enqueue_script('wp-multi-accordion-script', plugins_url('assets/js/script.js', __FILE__), ['jquery'], '1.1.0', true);  // Using jQuery as a dependency and loading the script in the footer.
}
add_action('wp_enqueue_scripts', 'wp_multi_accordion_enqueue_assets');

// Display content for "Add New Accordion" submenu page
function render_add_accordion_page() {
    // Check if form is submitted to add a new accordion
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accordion_name'])) {
        $accordion_name = sanitize_text_field($_POST['accordion_name']);
        $unique_accordion_key = 'accordion_' . uniqid();

        $accordion_data = array(
            'name' => $accordion_name,
            'questions' => array()
        );

        update_option($unique_accordion_key, $accordion_data);

        echo "<div class='notice notice-success'>";
        echo "<p>Accordion added successfully. Use the following shortcode to display the accordion:</p>";
        echo "<code>[faq_accordion id='{$unique_accordion_key}']</code>";
        echo "</div>";
    }

    // Display form to add new accordion
    echo '<h1>Add New Accordion</h1>';
    echo '<form method="post" action="">
        <input type="text" name="accordion_name" placeholder="Enter new accordion name" required>
        <input type="submit" name="add_accordion" value="Add Accordion">
        ' . wp_nonce_field('add_accordion') . '
    </form>';
}

// Display the FAQ Accordion on the frontend using a shortcode
function render_accordion_shortcode($atts) {
    $atts = shortcode_atts(
        array('id' => ''),
        $atts,
        'faq_accordion'
    );

    $accordion_data = get_option($atts['id'], false);

    // Check if accordion data exists
    if (!$accordion_data) {
        return "Accordion not found.";
    }

    $output = '<div class="accordion">';
    foreach ($accordion_data['questions'] as $key => $question) {
        $output .= '<div class="accordion-item">';
        $output .= '<div id="accordion-question-' . $key . '" class="accordion-question">' . esc_html($question['title']) . '</div>';
        
        $output .= '<div id="accordion-content-' . $key . '" class="accordion-content">' . wp_kses_post($question['content']) . '</div>';

        $output .= '</div>';
    }
    $output .= '</div>';
    return $output;
}
add_shortcode('faq_accordion', 'render_accordion_shortcode');

?>
