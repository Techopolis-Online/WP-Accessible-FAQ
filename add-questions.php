
<?php

// Function to render the Add Questions page
function render_add_questions_page() {
    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['question_title'], $_POST['question_content'], $_POST['accordion_id']) && wp_verify_nonce($_POST['_wpnonce'], 'add_question')) {
        // Sanitize input data
        $question_title = sanitize_text_field($_POST['question_title']);
        $question_content = sanitize_textarea_field($_POST['question_content']);
        $accordion_id = sanitize_text_field($_POST['accordion_id']);

        // Fetch the accordion's data
        $accordion_data = get_option($accordion_id);

        // Append new question to the accordion's questions array
        $accordion_data['questions'][] = array(
            'title' => $question_title,
            'content' => $question_content,
        );

        // Update the accordion data with the new question
        update_option($accordion_id, $accordion_data);

        // Success message
        echo "<div class='notice notice-success'><p>Question added successfully.</p></div>";
    }

    // Fetch all accordions to list them in the form
    $all_options = wp_load_alloptions();
    $accordions = array();
    foreach ($all_options as $key => $value) {
        if (strpos($key, 'accordion_') === 0) {
            $accordions[$key] = unserialize($value);
        }
    }

    // Display form to add a new question
    echo '<div class="wrap">';
    echo '<h1>Add New Question</h1>';
    echo '<form method="post" action="">';
    echo '<label for="accordion_id">Select Accordion:</label>';
    echo '<select name="accordion_id" id="accordion_id" required>';
    foreach ($accordions as $key => $accordion) {
        echo '<option value="' . esc_attr($key) . '">' . esc_html($accordion['name']) . '</option>';
    }
    echo '</select>';
    echo '<p><label for="question_title">Question Title:</label>';
    echo '<input type="text" id="question_title" name="question_title" required></p>';
    echo '<p><label for="question_content">Question Content:</label>';
    echo '<textarea id="question_content" name="question_content" required></textarea></p>';
    echo '<input type="submit" name="add_question" value="Add Question">';
    echo wp_nonce_field('add_question');
    echo '</form>';
    echo '</div>';
}

?>