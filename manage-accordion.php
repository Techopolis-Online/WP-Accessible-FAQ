<?php
function render_manage_accordions_page() {
    // Fetch accordions from options
    $accordions = get_option('wp_accessible_faq_accordions', array());
    // Fetch questions from options
    $questions = get_option('wp_accessible_faq_questions', array());

    echo '<div class="wrap">';
    echo '<h2>Manage Accordions</h2>';

    // Loop through each accordion and display its questions
    foreach ($accordions as $accordion_id => $accordion_name) {
        echo '<h3>' . esc_html($accordion_name) . '</h3>';

        // Filter questions for the current accordion
        $accordion_questions = array_filter($questions, function($question) use ($accordion_id) {
            return $question['accordion_id'] == $accordion_id;
        });

        if (empty($accordion_questions)) {
            echo '<p>No questions in this accordion.</p>';
        } else {
            echo '<ul>';
            foreach ($accordion_questions as $question) {
                echo '<li><strong>' . esc_html($question['title']) . ':</strong> ' . esc_html($question['content']) . '</li>';
            }
            echo '</ul>';
        }
    }

    echo '</div>';
}
