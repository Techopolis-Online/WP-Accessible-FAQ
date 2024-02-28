
<?php
function render_manage_accordions_page() {
    // Fetch all options and filter out those that are accordions
    $all_options = wp_load_alloptions();
    $accordions = array();
    foreach ($all_options as $key => $value) {
        if (strpos($key, 'accordion_') === 0) {  // Assuming accordion options start with 'accordion_'
            $accordions[$key] = maybe_unserialize($value);
        }
    }

    echo '<div class="wrap">';
    echo '<h2>Manage Accordions</h2>';

    if (empty($accordions)) {
        echo '<p>No accordions found.</p>';
    } else {
        echo '<table class="widefat">';
        echo '<thead>';
        echo '<tr><th>Accordion Name</th><th>Shortcode</th><th>Number of Questions</th></tr>';
        echo '</thead>';
        echo '<tbody>';

        foreach ($accordions as $accordion_id => $accordion) {
            $details_url = admin_url('admin.php?page=wp-accessible-faq-accordion-details&accordion_id=' . $accordion_id);
            $shortcode = '[faq_accordion id="' . esc_attr($accordion_id) . '"]';
            $num_questions = isset($accordion['questions']) ? count($accordion['questions']) : 0;

            echo '<tr>';
            echo '<td><a href="' . esc_url($details_url) . '">' . esc_html($accordion['name']) . '</a></td>';
            echo '<td><input type="text" onfocus="this.select();" readonly value="' . esc_attr($shortcode) . '" class="large-text code"></td>';
            echo '<td>' . esc_html($num_questions) . '</td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
    }

    echo '</div>';
}