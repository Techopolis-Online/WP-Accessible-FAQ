<?php
function render_accordion_details_page() {
    wp_enqueue_script('jquery');

    $accordion_id = isset($_GET['accordion_id']) ? sanitize_text_field($_GET['accordion_id']) : '';

    // Handle POST request for both updating and deleting questions
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'], $_POST['question_index'])) {
        $question_index = intval($_POST['question_index']);
        $accordion = get_option($accordion_id);

        if ($accordion && isset($accordion['questions'][$question_index])) {
            if ($_POST['action'] == 'update_question') {
                $accordion['questions'][$question_index]['title'] = sanitize_text_field($_POST['question_title']);
                $accordion['questions'][$question_index]['content'] = sanitize_text_field($_POST['question_content']);
                update_option($accordion_id, $accordion);
                echo '<div class="notice notice-success"><p>Question updated successfully.</p></div>';
            } elseif ($_POST['action'] == 'delete_question' && check_admin_referer('delete_question')) {
                array_splice($accordion['questions'], $question_index, 1);
                update_option($accordion_id, $accordion);
                echo '<div class="notice notice-success"><p>Question deleted successfully.</p></div>';
            }
        }
    }

    $accordion = get_option($accordion_id);
    if (!$accordion) {
        echo '<div class="wrap"><h2>Accordion Not Found</h2></div>';
        return;
    }

    // Display accordion details and questions
    echo '<div class="wrap">';
    echo '<h1>Accordion Details: ' . esc_html($accordion['name']) . '</h1>';

    if (!empty($accordion['questions'])) {
        echo '<table class="widefat"><thead><tr><th>Question</th><th>Content</th><th>Actions</th></tr></thead><tbody>';
        foreach ($accordion['questions'] as $index => $question) {
            echo '<tr>';
            echo '<form method="POST" action="">';
            echo '<td><input type="text" name="question_title" value="' . esc_attr($question['title']) . '"></td>';
            echo '<td><textarea name="question_content">' . esc_textarea($question['content']) . '</textarea></td>';
            echo '<td>';
            echo '<input type="hidden" name="question_index" value="' . $index . '">';
            echo '<input type="hidden" name="action" value="update_question">';
            echo '<input type="submit" value="Update" class="button">';
            echo '</form>';
            echo '<form method="POST" action="" style="display:inline;">';
            echo '<input type="hidden" name="question_index" value="' . $index . '">';
            echo '<input type="hidden" name="action" value="delete_question">';
            wp_nonce_field('delete_question');
            echo '<input type="submit" value="Delete" class="button" onclick="return confirm(\'Are you sure you want to delete this question?\');">';
            echo '</form>';
            echo '</td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
    } else {
        echo '<p>No questions found in this accordion.</p>';
    }

    echo '</div>'; // Close .wrap
}
?>
