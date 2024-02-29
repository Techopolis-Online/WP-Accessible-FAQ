<?php
function render_accordion_details_page() {
    // Get the accordion ID from the URL query parameter
    $accordion_id = isset($_GET['accordion_id']) ? sanitize_text_field($_GET['accordion_id']) : '';

    // Check for a delete action and process it before fetching the updated accordion data
    if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['question_index']) && check_admin_referer('delete_question')) {
        $accordion = get_option($accordion_id);
        if (!empty($accordion) && isset($accordion['questions'][intval($_GET['question_index'])])) {
            // Remove the question from the array
            array_splice($accordion['questions'], intval($_GET['question_index']), 1);
            // Update the accordion data in the database
            update_option($accordion_id, $accordion);
            echo '<div class="notice notice-success"><p>Question deleted successfully.</p></div>';
        }
    }

    // Fetch the updated accordion data
    $accordion = get_option($accordion_id);

    // Check if the accordion exists
    if (!$accordion) {
        echo '<div class="wrap"><h2>Accordion Not Found</h2></div>';
        return;
    }

    // Display accordion details
    echo '<div class="wrap">';
    echo '<h1>Accordion Details: ' . esc_html($accordion['name']) . '</h1>';

    // List questions with delete functionality
    if (!empty($accordion['questions'])) {
        echo '<table class="widefat">';
        echo '<thead><tr><th>Question</th><th>Actions</th></tr></thead>';
        echo '<tbody>';

        foreach ($accordion['questions'] as $index => $question) {
            // Correct the action URL to reflect the details page and the proper nonce generation
            $delete_nonce_url = wp_nonce_url(admin_url('admin.php?page=wp-accessible-faq-accordion-details&accordion_id=' . $accordion_id . '&action=delete&question_index=' . $index), 'delete_question');
            echo '<tr>';
            echo '<td>' . esc_html($question['title']) . '</td>';
            echo '<td>';
            // Placeholder for the edit button
            echo '<a href="#" class="button">Edit</a> ';
            // Delete button with nonce URL for security
            echo '<a href="' . $delete_nonce_url . '" class="button" onclick="return confirm(\'Are you sure you want to delete this question?\')">Delete</a>';
            echo '</td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
    } else {
        echo '<p>No questions found in this accordion.</p>';
    }

    echo '</div>';
}
?>
