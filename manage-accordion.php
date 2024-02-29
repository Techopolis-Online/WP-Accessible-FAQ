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

    // Handle POST request for updating or deleting accordion details
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'], $_POST['accordion_id'])) {
        $accordion_id = sanitize_text_field($_POST['accordion_id']);
        if (isset($accordions[$accordion_id])) {
            if ($_POST['action'] == 'update_accordion') {
                // Update accordion name
                $accordions[$accordion_id]['name'] = sanitize_text_field($_POST['accordion_name']);
                update_option($accordion_id, $accordions[$accordion_id]);
                echo '<div class="notice notice-success"><p>Accordion updated successfully.</p></div>';
            } elseif ($_POST['action'] == 'delete_accordion' && check_admin_referer('delete_accordion')) {
                // Delete accordion
                delete_option($accordion_id);
                echo '<div class="notice notice-success"><p>Accordion deleted successfully.</p></div>';
                unset($accordions[$accordion_id]); // Remove the accordion from the local array to update the UI
            }
        }
    }

    echo '<div class="wrap">';
    echo '<h2>Manage Accordions</h2>';
    echo '<a href="' . esc_url(admin_url('admin.php?page=add_new_accordion')) . '" class="button">Add New Accordion</a>';

    if (empty($accordions)) {
        echo '<p>No accordions found. <a href="' . esc_url(admin_url('admin.php?page=add_new_accordion')) . '">Add your first accordion.</a></p>';
    } else {
        echo '<table class="widefat">';
        echo '<thead>';
        echo '<tr><th>Accordion Name</th><th>Shortcode</th><th>Number of Questions</th><th>Actions</th></tr>';
        echo '</thead>';
        echo '<tbody>';

        foreach ($accordions as $accordion_id => $accordion) {
            $shortcode = '[faq_accordion id="' . esc_attr($accordion_id) . '"]';
            $num_questions = isset($accordion['questions']) ? count($accordion['questions']) : 0;

            echo '<tr>';
            echo '<form method="POST" action="">';
            echo '<td><input type="text" name="accordion_name" value="' . esc_attr($accordion['name']) . '"></td>';
            echo '<td><input type="text" onfocus="this.select();" readonly value="' . esc_attr($shortcode) . '" class="large-text code"></td>';
            echo '<td>' . esc_html($num_questions) . '</td>';
            echo '<td>';
            echo '<input type="hidden" name="accordion_id" value="' . esc_attr($accordion_id) . '">';
            echo '<input type="hidden" name="action" value="update_accordion">';
            echo '<input type="submit" value="Update" class="button">';
            echo '</form> ';
            echo '<form method="POST" action="" style="display:inline;">';
            echo '<input type="hidden" name="accordion_id" value="' . esc_attr($accordion_id) . '">';
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
