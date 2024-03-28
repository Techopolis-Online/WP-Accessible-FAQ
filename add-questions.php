<<<<<<< HEAD

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
=======
<?php

// Function to display form for adding questions to existing accordions
function render_add_questions_page() {
    // Check if form was submitted and process it
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['selected_accordion'], $_POST['question'], $_POST['answer'])) {
        $selected_accordion = sanitize_text_field($_POST['selected_accordion']);
        $question = sanitize_text_field($_POST['question']);
        $answer = sanitize_textarea_field($_POST['answer']);

        $accordion_data = get_option($selected_accordion, false);

        if ($accordion_data) {
            $accordion_data['questions'][] = array(
                'title' => $question,
                'content' => $answer
            );

            update_option($selected_accordion, $accordion_data);
            echo "<div class='notice notice-success'>Question added successfully to the accordion.</div>";
        } else {
            echo "<div class='notice notice-error'>Selected accordion not found.</div>";
        }
    }

    // Fetch all accordions from the options
    $all_accordions = array();
    foreach (wp_load_alloptions() as $key => $value) {
        if (strpos($key, 'accordion_') === 0) {
            $all_accordions[$key] = maybe_unserialize($value);
        }
    }

    if (empty($all_accordions)) {
        echo "<div class='notice notice-info'>No accordions found. Please add an accordion first.</div>";
        return;
    }

    // Display the form
    echo '<h2>Add Question to an Accordion</h2>';
    echo '<form method="post" action="">';
    echo '<div>';
    echo '<label for="selected_accordion">Select Accordion:</label>';
    echo '<select name="selected_accordion" id="selected_accordion" required>';
    foreach ($all_accordions as $key => $value) {
        echo '<option value="' . esc_attr($key) . '">' . esc_html($value['name']) . '</option>';
    }
    echo '</select>';
    echo '</div>';

    echo '<div>';
    echo '<label for="question">Question:</label>';
    echo '<input type="text" name="question" id="question" placeholder="Enter your question" required>';
    echo '</div>';

    echo '<div>';
    echo '<label for="answer">Answer:</label>';
    echo '<textarea name="answer" id="answer" placeholder="Enter the answer" rows="4" required></textarea>';
    echo '</div>';

    echo '<div>';
    echo '<input type="submit" value="Add Question">';
    echo '</div>';
    echo '</form>';
}
?>
>>>>>>> cb18b78294e0fd7f37e020c60fe677895a9e7597
