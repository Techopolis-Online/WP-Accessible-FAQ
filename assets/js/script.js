document.addEventListener('DOMContentLoaded', function() {

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

    // Transform .accordion-question divs by wrapping them in h3
    var accordions = document.querySelectorAll('.accordion-question');
    accordions.forEach(function(accordion) {
        var h3 = document.createElement('h3');
        accordion.parentNode.insertBefore(h3, accordion);
        h3.appendChild(accordion);
    });

    // Re-select accordions after transformation
    accordions = document.querySelectorAll('.accordion-question');

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
    toggleAllButton.className = 'accordion-toggle-all';
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

    // Append the button at the top of the accordion container
    if (accordions.length > 0) {
        var accordionContainer = accordions[0].closest('.accordion');
        accordionContainer.insertBefore(toggleAllButton, accordionContainer.firstChild);
    }
});
