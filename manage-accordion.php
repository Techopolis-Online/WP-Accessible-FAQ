<?php
function render_manage_accordions_page() {
    // Handle delete accordion action
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'], $_POST['delete_accordion']) && $_POST['action'] === 'delete_accordion') {
        $accordion_id = sanitize_text_field($_POST['delete_accordion']);
        delete_option($accordion_id);
        echo '<div class="notice notice-success"><p>Accordion deleted successfully.</p></div>';
    }

    // Handle update accordion name action
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'], $_POST['update_accordion_name']) && $_POST['action'] === 'update_accordion_name') {
        $accordion_id = sanitize_text_field($_POST['update_accordion_name']);
        $new_name = sanitize_text_field($_POST['accordion_name']);
        $all_options = wp_load_alloptions();
        if (isset($all_options[$accordion_id])) {
            $accordion = maybe_unserialize($all_options[$accordion_id]);
            $accordion['name'] = $new_name;
            update_option($accordion_id, $accordion);
            echo '<div class="notice notice-success"><p>Accordion name updated successfully.</p></div>';
        }
    }

    // Fetch all options and filter out those that are accordions
    $all_options = wp_load_alloptions();
    $accordions = array();
    foreach ($all_options as $key => $value) {
        if (strpos($key, 'accordion_') === 0) {
            $accordions[$key] = maybe_unserialize($value);
        }
    }

    echo '<div class="wrap">';
    echo '<h2>Manage Accordions</h2>';

    // Add New Accordion link
    echo '<p><a href="' . admin_url('admin.php?page=wp-accessible-faq-add-accordion') . '" class="button-primary">Add New Accordion</a></p>';

    if (empty($accordions)) {
        echo '<p>No accordions found.</p>';
    } else {
        echo '<table class="widefat">';
        echo '<thead>';
        echo '<tr><th>Accordion Name</th><th>Shortcode</th><th>Number of Questions</th><th>Actions</th></tr>';
        echo '</thead>';
        echo '<tbody>';

        foreach ($accordions as $accordion_id => $accordion) {
            $details_url = admin_url('options-general.php?page=wp-accessible-faq-accordion-details&accordion_id=' . $accordion_id);
            $shortcode = '[faq_accordion id="' . esc_attr($accordion_id) . '"]';
            $num_questions = isset($accordion['questions']) ? count($accordion['questions']) : 0;

            echo '<tr>';
            echo '<form method="POST" action="">';
            echo '<td><input type="text" name="accordion_name" value="' . esc_attr($accordion['name']) . '" class="accordion-name-input"></td>';
            echo '<td><input type="text" onfocus="this.select();" readonly value="' . esc_attr($shortcode) . '" class="large-text code"></td>';
            echo '<td>' . esc_html($num_questions) . '</td>';
            echo '<td>';
            echo '<input type="hidden" name="update_accordion_name" value="' . esc_attr($accordion_id) . '">';
            echo '<input type="hidden" name="action" value="update_accordion_name">';
            wp_nonce_field('update_accordion_name');
            echo '<input type="submit" value="Update Name" class="button">';
            echo '</form>';
            echo '<a href="' . esc_url($details_url) . '">View Details</a>';
            echo '<form method="POST" action="" style="display:inline;">';
            echo '<input type="hidden" name="delete_accordion" value="' . esc_attr($accordion_id) . '">';
            echo '<input type="hidden" name="action" value="delete_accordion">';
            wp_nonce_field('delete_accordion');
            echo '<input type="submit" value="Delete" class="button" onclick="return confirm(\'Are you sure you want to delete this accordion?\');">';
            echo '</form>';
            echo '</td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
    }

    echo '</div>';
}
?>
