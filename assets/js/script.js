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
        if (content) {
            content.style.display = 'none';
        } else {
            console.error('Content element not found for accordion:', accordion);
        }
        
        accordion.addEventListener('click', function() {
            toggleAccordion(accordion);
        });

        // Change the heading level to h2 for the question button
        var question = accordion.querySelector('.accordion-question');
        if (question) {
            var questionText = question.textContent;
            var h2Element = document.createElement('h2');
            h2Element.className = 'accordion-question';
            h2Element.textContent = questionText;
            question.replaceWith(h2Element);
        } else {
            console.error('Question element not found for accordion:', accordion);
        }

        accordion.addEventListener('keydown', function(event) {
            if (event.key === "Enter" || event.key === " ") {
                event.preventDefault();
                toggleAccordion(accordion);
            }
        });
    });

    // Add an h2 tag for the toggle all button
    var toggleAllButton = document.createElement('button');
    toggleAllButton.textContent = 'Expand All';
    toggleAllButton.addEventListener('click', function() {
        var allOpen = true;
        accordions.forEach(function(accordion) {
            var content = document.getElementById('accordion-content-' + accordion.id.split('-')[2]);
            if (content && content.style.display === 'none') {
                allOpen = false;
            }
        });
        
        accordions.forEach(function(accordion) {
            var content = document.getElementById('accordion-content-' + accordion.id.split('-')[2]);
            if (content) {
                if(allOpen) {
                    content.style.display = 'none';
                    accordion.setAttribute('aria-expanded', 'false');
                } else {
                    content.style.display = 'block';
                    accordion.setAttribute('aria-expanded', 'true');
                }
            }
        });
        
        toggleAllButton.textContent = allOpen ? 'Expand All' : 'Collapse All';
    });

    // Append the h2 tag before the first accordion
    var firstAccordion = accordions[0];
    if (firstAccordion) {
        firstAccordion.parentNode.insertBefore(toggleAllButton, firstAccordion);
    } else {
        console.error('No accordions found.');
    }
});
