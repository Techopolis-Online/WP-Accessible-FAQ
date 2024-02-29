<?php
function render_manage_accordions_page() {
    // Handle delete action
    if (isset($_GET['action'], $_GET['accordion_id']) && $_GET['action'] === 'delete') {
        $accordion_id = sanitize_text_field($_GET['accordion_id']);
        delete_option($accordion_id);
        wp_redirect(admin_url('options-general.php?page=wp-accessible-faq-manage-accordions'));
        exit;
    }

    // Handle accordion name update
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accordion_name'], $_POST['accordion_id'])) {
        $accordion_name = sanitize_text_field($_POST['accordion_name']);
        $accordion_id = sanitize_text_field($_POST['accordion_id']);
        $accordion = get_option($accordion_id);
        if (!empty($accordion)) {
            $accordion['name'] = $accordion_name;
            update_option($accordion_id, $accordion);
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

    // Add Accordion link
    echo '<p><a href="' . admin_url('options-general.php?page=wp-accessible-faq-add-accordion') . '" class="button-primary">Add Accordion</a></p>';

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
            $delete_url = admin_url('options-general.php?page=wp-accessible-faq-manage-accordions&action=delete&accordion_id=' . $accordion_id);
            $shortcode = '[faq_accordion id="' . esc_attr($accordion_id) . '"]';
            $num_questions = isset($accordion['questions']) ? count($accordion['questions']) : 0;

            echo '<tr>';
            echo '<form method="post" action="">'; // Form for updating accordion name
            echo '<td><input type="text" name="accordion_name" value="' . esc_attr($accordion['name']) . '"></td>';
            echo '<td><input type="text" onfocus="this.select();" readonly value="' . esc_attr($shortcode) . '" class="large-text code"></td>';
            echo '<td>' . esc_html($num_questions) . '</td>';
            echo '<td>';
            echo '<a href="' . esc_url($details_url) . '">View Details</a>';
            echo '<input type="hidden" name="accordion_id" value="' . esc_attr($accordion_id) . '">'; // Hidden field for accordion ID
            echo '<input type="submit" value="Update Name" class="button-primary">';
            echo '<a href="' . esc_url($delete_url) . '" onclick="return confirm(\'Are you sure you want to delete this accordion?\')">Delete</a>';
            echo '</td>';
            echo '</form>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
    }

    echo '</div>';
}
?>
