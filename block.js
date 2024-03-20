(function (blocks, element, components, apiFetch) {
    var el = element.createElement;
    var registerBlockType = blocks.registerBlockType;
    var SelectControl = components.SelectControl;
    var useEffect = element.useEffect;
    var useState = element.useState;

    registerBlockType('wp/v2/accordion-selector', {
        title: 'Accordion Selector',
        icon: 'list-view',
        category: 'widgets',

        attributes: {
            selectedAccordion: {
                type: 'string',
            },
        },

        edit: function (props) {
            var [accordions, setAccordions] = useState([]);
            var selectedAccordion = props.attributes.selectedAccordion;

            useEffect(() => {
                apiFetch({ path: '/wp/v2/accordions' }).then((items) => {
                    setAccordions(items);
                });
            }, []);

            function onChangeAccordion(selected) {
                props.setAttributes({ selectedAccordion: selected });
            }

            return el('div', {},
                el(SelectControl, {
                    label: 'Select an Accordion',
                    value: selectedAccordion,
                    options: accordions.map(function (accordion) {
                        return { label: accordion.name, value: accordion.id };
                    }),
                    onChange: onChangeAccordion,
                }),
                selectedAccordion && el('p', {}, 'Shortcode: [accordion id="' + selectedAccordion + '"]')
            );
        },

        save: function () {
            return null; // Content is rendered in PHP and thus save returns null.
        },
    });
})(window.wp.blocks, window.wp.element, window.wp.components, window.wp.apiFetch);
