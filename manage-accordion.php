
<?php
function render_manage_accordions_page() {
    // Fetch all options and filter out those that are accordions
    $all_options = wp_load_alloptions();
    $accordions = array();
    foreach ($all_options as $key => $value) {
        if (strpos($key, 'accordion_') === 0) {
            $accordions[$key] = unserialize($value);
        }
    }

    echo '<div class="wrap">';
    echo '<h2>Manage Accordions</h2>';

    // Check if there are any accordions
    if (empty($accordions)) {
        echo '<p>No accordions found.</p>';
    } else {
        echo '<table class="widefat">';
        echo '<thead>';
        echo '<tr><th>Name</th><th>Shortcode</th><th>Actions</th></tr>';
        echo '</thead>';
        echo '<tbody>';

        foreach ($accordions as $key => $accordion) {
            $shortcode = '[faq_accordion id="' . esc_attr($key) . '"]';
            echo '<tr>';
            echo '<td>' . esc_html($accordion['name']) . '</td>';
            echo '<td><input type="text" readonly value="' . esc_attr($shortcode) . '" class="large-text code" onclick="this.select();"></td>';
            // Placeholder for link to accordion details - you'll need to implement the actual link or functionality
            echo '<td><a href="#">Details</a></td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
    }

    echo '</div>';
}