<?php
    if (get_option('utt_related_tour', 'off') != 'on') {
        return false;
    }

    $region = wp_get_post_terms(get_the_ID(), 'region');
    $regionByTour = array();
    foreach ($region as $item) :
        $regionByTour[] = $item->term_id;
    endforeach;

    $args = array(
        'post_type' => UTTTravelTour::$postType,
        'posts_per_page' => 3,
        'post__not_in' => array(get_the_ID()),
        'taxonomy' => array(
            array(
                'taxonomy' => UTTTravelTour::regionTour,
                'field'    => 'term_id',
                'terms' => $regionByTour,
                'operator' => 'IN'
            )
        )
    );
    $args = apply_filters('utttour_related_parameters', $args);
    $tours = new WP_Query($args);
?>

<?php if ($tours->have_posts()) : ?>
    <div id="section2" class="ut-product__section ut-product__photos">
        <h3 class="h5"><?php _e('Related Tours:'); ?> </h3>
        <div class="ut-product__related">
            <div class="ut-row">
                <?php
                while ($tours->have_posts()) {
                    $tours->the_post();
                    global $post;
                    echo '<div class="ut-col-12 ut-col-lg-6 ut-col-xl-4 ut-collection__product-col">';
                    UTTLoadTemplatePart('loop/widget-item', 'content');
                    echo '</div>';
                }

                wp_reset_query();
                wp_reset_postdata();
                ?>
            </div>
        </div>
    </div>
<?php endif; ?>

