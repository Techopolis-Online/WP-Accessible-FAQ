<?php
// Shortcode to Display FAQs
function faq_accordion_shortcode() {
    ob_start();

    $args = array(
        'post_type'      => 'faq',
        'posts_per_page' => -1,
        'order'          => 'ASC',
        'orderby'        => 'title',
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) :
    ?>
    <div class="faq-accordion">
        <?php while ($query->have_posts()) : $query->the_post(); ?>
            <?php
            $faq_id = get_the_ID();
            ?>
            <div class="accordion-item">
                <button id="button-<?php echo $faq_id; ?>" class="accordion-button" aria-expanded="false" aria-controls="content-<?php echo $faq_id; ?>" tabindex="0">
                    <?php the_title('<h2>', '</h2>'); ?>
                </button>
                <div id="content-<?php echo $faq_id; ?>" class="accordion-content" aria-hidden="true" tabindex="0">
                    <?php the_content(); ?>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
    <?php
    wp_reset_postdata();
    endif;

    return ob_get_clean();
}

add_shortcode('faq_accordion', 'faq_accordion_shortcode');
