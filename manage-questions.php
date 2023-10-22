<?php
// include files
include_once('submenu-options.php');


// Function to display content for "Manage Questions" submenu page
function render_manage_questions_page() {
    $questions = get_option('wp_accessible_faq_questions', array());

    // Process question deletion
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_question']) && wp_verify_nonce($_POST['_wpnonce'], 'delete_question')) {
        $id = sanitize_text_field($_POST['question_id']);
        if (isset($questions[$id])) {
            unset($questions[$id]);
            update_option('wp_accessible_faq_questions', $questions);
            echo '<div class="updated"><p>Question deleted successfully.</p></div>';
        }
    }

    // Display all questions in a table
    echo '<h1>Manage Questions</h1>';
    echo '<table class="widefat">
        <thead>
            <tr>
                <th>ID</th>
                <th>Question</th>
                <th>Answer</th>
                <th>Accordion ID</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>';
    foreach ($questions as $id => $question) {
        echo '<tr>
            <td>' . esc_html($id) . '</td>
            <td>' . esc_html($question['title']) . '</td>
            <td>' . wp_trim_words(esc_html($question['content']), 15) . '</td>
            <td>' . esc_html($question['accordion_id']) . '</td>
            <td>
                <form method="post" action="">
                    ' . wp_nonce_field('delete_question') . '
                    <input type="hidden" name="question_id" value="' . esc_attr($id) . '">
                    <input type="submit" name="delete_question" value="Delete" onclick="return confirm(\'Are you sure you want to delete this question?\')">
                </form>
            </td>
        </tr>';
    }
    echo '</tbody>
    </table>';
}