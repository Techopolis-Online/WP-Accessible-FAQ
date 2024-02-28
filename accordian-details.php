<?php
function render_accordion_details_page() {
    // Get the accordion ID from the URL query parameter
    $accordion_id = isset($_GET['accordion_id']) ? sanitize_text_field($_GET['accordion_id']) : '';

    // Fetch the accordion data
    $accordion = get_option($accordion_id);

    // Check if the accordion exists
    if (!$accordion) {
        echo '<div class="wrap"><h2>Accordion Not Found</h2></div>';
        return;
    }

    // Display accordion details
    echo '<div class="wrap">';
    echo '<h1>Accordion Details: ' . esc_html($accordion['name']) . '</h1>';

    // List questions
    if (!empty($accordion['questions'])) {
        echo '<table class="widefat">';
        echo '<thead><tr><th>Question</th><th>Actions</th></tr></thead>';
        echo '<tbody>';

        foreach ($accordion['questions'] as $index => $question) {
            echo '<tr>';
            echo '<td>' . esc_html($question['title']) . '</td>';
            echo '<td>';
            // Edit button: Ideally, this should link to a question editing page with pre-populated question data
            echo '<a href="' . admin_url('admin.php?page=edit_question&accordion_id=' . $accordion_id . '&question_index=' . $index) . '" class="button">Edit</a> ';
            // Delete button: Should lead to a deletion confirmation page or trigger a deletion action
            echo '<a href="' . admin_url('admin.php?page=delete_question&accordion_id=' . $accordion_id . '&question_index=' . $index) . '" class="button">Delete</a>';
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