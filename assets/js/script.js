document.addEventListener('DOMContentLoaded', function() {
    var accordions = document.querySelectorAll('.accordion-question');

    function toggleAccordion(accordion) {
        var contentID = 'accordion-content-' + accordion.id.split('-')[2];
        var content = document.getElementById(contentID);
        
        var isExpanded = accordion.getAttribute('aria-expanded') === 'true';

        if (isExpanded) {
            content.style.display = 'none';
            accordion.setAttribute('aria-expanded', 'false');
        } else {
            content.style.display = 'block';
            accordion.setAttribute('aria-expanded', 'true');
        }
    }

    accordions.forEach(function(accordion) {
        var contentID = 'accordion-content-' + accordion.id.split('-')[2];

        // Set ARIA roles and attributes
        accordion.setAttribute('role', 'button');
        accordion.setAttribute('aria-controls', contentID);
        accordion.setAttribute('aria-expanded', 'false');
        
        // Initially hide all the accordion contents
        var content = document.getElementById(contentID);
        content.style.display = 'none';
        
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

    // Add a button to expand/collapse all accordions
    var toggleAllButton = document.createElement('button');
    toggleAllButton.textContent = 'Expand All';
    toggleAllButton.addEventListener('click', function() {
        var allOpen = true;
        accordions.forEach(function(accordion) {
            var content = document.getElementById('accordion-content-' + accordion.id.split('-')[2]);
            if(content.style.display === 'none') {
                allOpen = false;
            }
        });
        
        accordions.forEach(function(accordion) {
            var content = document.getElementById('accordion-content-' + accordion.id.split('-')[2]);
            if(allOpen) {
                content.style.display = 'none';
                accordion.setAttribute('aria-expanded', 'false');
            } else {
                content.style.display = 'block';
                accordion.setAttribute('aria-expanded', 'true');
            }
        });
        
        toggleAllButton.textContent = allOpen ? 'Expand All' : 'Collapse All';
    });

    // Append the button before the first accordion
    var firstAccordion = accordions[0];
    firstAccordion.parentNode.insertBefore(toggleAllButton, firstAccordion);
});
