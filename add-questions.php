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
