document.addEventListener('DOMContentLoaded', function() {
    var accordions = document.querySelectorAll('.accordion-button');
    var allExpanded = false; // state to check if all are expanded or collapsed

    function toggleAccordion(accordion) {
        var content = document.getElementById('content-' + accordion.id.split('-')[1]);
        var expanded = accordion.getAttribute('aria-expanded') === 'true';
        accordion.setAttribute('aria-expanded', !expanded);
        content.setAttribute('aria-hidden', expanded);
        if(expanded){
            content.style.maxHeight = '0';
        } else {
            content.style.maxHeight = content.scrollHeight + "px";
        }
    }

    accordions.forEach(function(accordion) {
        accordion.addEventListener('click', function() {
            toggleAccordion(accordion);
        });

        accordion.addEventListener('keydown', function(event) {
            if (event.key === "Enter" || event.key === " ") {
                event.preventDefault();
                toggleAccordion(accordion);
            }
        });
    });

    // Create a button to expand/collapse all accordions
    var toggleAllButton = document.createElement('button');
    toggleAllButton.id = 'toggleAll';
    toggleAllButton.textContent = 'Expand All';

    toggleAllButton.addEventListener('click', function() {
        accordions.forEach(function(accordion) {
            if (allExpanded) {
                if (accordion.getAttribute('aria-expanded') === 'true') {
                    toggleAccordion(accordion);
                }
            } else {
                if (accordion.getAttribute('aria-expanded') === 'false' || accordion.getAttribute('aria-expanded') === null) {
                    toggleAccordion(accordion);
                }
            }
        });
        
        allExpanded = !allExpanded; // Toggle the state after all accordions have been expanded/collapsed
        toggleAllButton.textContent = allExpanded ? "Collapse All" : "Expand All"; // Update the button text
    });

    // Add the button before the first accordion
    var firstAccordion = accordions[0];
    firstAccordion.parentNode.insertBefore(toggleAllButton, firstAccordion);
});
