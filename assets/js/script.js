document.addEventListener('DOMContentLoaded', function() {
    var accordions = document.querySelectorAll('.accordion-button');
    accordions.forEach(function(accordion) {
        accordion.addEventListener('click', function() {
            var content = document.getElementById('content-' + accordion.id.split('-')[1]);
            var expanded = accordion.getAttribute('aria-expanded') === 'true';
            accordion.setAttribute('aria-expanded', !expanded);
            content.setAttribute('aria-hidden', expanded);
            if(expanded){
                content.style.maxHeight = '0';
            }else{
                content.style.maxHeight = content.scrollHeight + "px";
            }
        });
        accordion.addEventListener('keydown', function(event) {
            if(event.key === "Enter" || event.key === " ") {
                event.preventDefault();
                accordion.click();
            }
        });
    });
});
