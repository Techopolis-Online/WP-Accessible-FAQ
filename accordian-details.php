<?php
function render_accordion_details_page() {
    // Enqueue jQuery for modal functionality - usually included in WP admin by default
    wp_enqueue_script('jquery');

    // Get the accordion ID from the URL query parameter
    $accordion_id = isset($_GET['accordion_id']) ? sanitize_text_field($_GET['accordion_id']) : '';

    // Check for a delete action and process it
    // Your existing deletion logic here...

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

    // List questions with edit functionality
    if (!empty($accordion['questions'])) {
        echo '<table class="widefat">';
        echo '<thead><tr><th>Question</th><th>Actions</th></tr></thead>';
        echo '<tbody>';

        foreach ($accordion['questions'] as $index => $question) {
            echo '<tr>';
            echo '<td id="question_text_' . $index . '">' . esc_html($question['title']) . '</td>';
            echo '<td>';
            // Edit button triggers a JavaScript function to open the modal
            echo '<button onclick="openEditModal(' . $index . ')" class="button">Edit</button> ';
            // Delete button (your existing delete functionality)
            echo '</td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
    } else {
        echo '<p>No questions found in this accordion.</p>';
    }

    // Modal HTML structure for editing
    echo '<div id="editModal" style="display:none; position:fixed; top:20%; left:50%; transform:translate(-50%, -50%); background:white; padding:20px; box-shadow:0 0 15px rgba(0,0,0,0.5); z-index:1000;">';
    echo '<textarea id="editQuestionText" style="width:300px; height:200px;"></textarea>';
    echo '<button onclick="saveQuestion()">Save</button>';
    echo '<button onclick="closeEditModal()">Cancel</button>';
    echo '</div>';

    // Overlay for modal
    echo '<div id="modalOverlay" onclick="closeEditModal()" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:500;"></div>';

    // JavaScript for modal behavior
    ?>
    <script>
    function openEditModal(index) {
        var questionText = jQuery('#question_text_' + index).text();
        jQuery('#editQuestionText').val(questionText);
        jQuery('#editModal').show();
        jQuery('#modalOverlay').show();
        window.currentEditingIndex = index; // Store the index of the question being edited
    }

    function closeEditModal() {
        jQuery('#editModal').hide();
        jQuery('#modalOverlay').hide();
    }

    function saveQuestion() {
        var updatedText = jQuery('#editQuestionText').val();
        // Display the updated text in the table (for demonstration purposes)
        // In a real application, you would send this to the server via AJAX
        jQuery('#question_text_' + window.currentEditingIndex).text(updatedText);
        closeEditModal();
    }
    </script>
    <?php

    echo '</div>'; // Close .wrap
}
?>
