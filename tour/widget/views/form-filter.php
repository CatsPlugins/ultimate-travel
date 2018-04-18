<?php

global $wp_query;

$region = get_terms(array(
    'taxonomy' => UTTTravelTour::regionTour,
));

$regionOptions = array();
if ($region && count($region) > 0) {
    foreach ($region as $item) {
        $regionOptions[$item->parent][] = $item;
    }
}


function getCheckBoxTerm($data, $key, $name) {
    $html = '';

    foreach ($data[$key] as $item) : ob_start();
        $status = '';
        if (isset($_GET['f'][$name]) && is_array($_GET['f'][$name]) && in_array($item->term_id, $_GET['f'][$name])) {
            $status = 'checked';
        }
    ?>
        <li class="ut-filter__item">
            <label class='ut-checkbox-group'>
                <input <?php echo $status ?> onchange="form.submit()" name="f[<?php echo $name ?>][]" type="checkbox" value='<?php echo $item->term_id ?>'>
                <span><b><?php echo $item->name ?></b></span>
            </label>
            <?php
                if (isset($data[$item->term_id])) {
                    echo '<ul class="ut-ul ut-filter__list">';
                    echo getCheckBoxTerm($data, $item->term_id, $name);
                    echo '</ul>';
                }
            ?>
        </li>
    <?php
        $html .= ob_get_clean();
    endforeach;

    return $html;
}

function uttRatinglFilterForm(){
    $ratingMin = (isset($_GET['f']['rating']['min']) ? number_format($_GET['f']['rating']['min'], 0 ,'.','') : 0);
    $ratingMax = (isset($_GET['f']['rating']['max']) ? number_format($_GET['f']['rating']['max'], 0 ,'.','') : 5);
    ?>
    <div class='ut-widget'>
        <h3 class="ut-widget__heading">
            <?php _e('Evaluate'); ?>
        </h3>
        <input type="hidden" id="r_rating_min" value="<?php echo $ratingMin ?>" name="f[rating][min]">
        <input type="hidden" id="r_rating_max" value="<?php echo $ratingMax ?>" name="f[rating][max]">
        <div class='ut-filter--rating-wrap'>

            <div
                    id="ut-filter-rating-slider"
                    data-max="5"
                    data-min="1"
                    data-step="1"
                    data-currentmin="<?php echo $ratingMin ?>"
                    data-currentmax="<?php echo $ratingMax ?>"
                    data-targetmax="#r_rating_max"
                    data-targetmin="#r_rating_min"
            ></div>


            <ul class="ut-ul ut-filter__list" id="ut-filter__list">
                <li class="ut-filter__item ut-filter__item--rating">
                        <span>
                            <i class="ut-icon-star"></i>
                            <i class="ut-icon-star"></i>
                            <i class="ut-icon-star"></i>
                            <i class="ut-icon-star"></i>
                            <i class="ut-icon-star"></i>
                        </span>
                </li>
                <li class="ut-filter__item ut-filter__item--rating">
                        <span>
                            <i class="ut-icon-star"></i>
                            <i class="ut-icon-star"></i>
                            <i class="ut-icon-star"></i>
                            <i class="ut-icon-star"></i>
                        </span>
                </li>
                <li class="ut-filter__item ut-filter__item--rating">
                        <span>
                            <i class="ut-icon-star"></i>
                            <i class="ut-icon-star"></i>
                            <i class="ut-icon-star"></i>
                        </span>
                </li>
                <li class="ut-filter__item ut-filter__item--rating">
                        <span>
                            <i class="ut-icon-star"></i>
                            <i class="ut-icon-star"></i>
                        </span>
                </li>
                <li class="ut-filter__item ut-filter__item--rating">
                    <span><i class="ut-icon-star"></i></span>
                </li>
            </ul>
        </div>
    </div>
    <?php
}



?>
<form action="">
<div class="ut-sidebar ut-filter">
    <?php
        $term   = get_queried_object();
        $postTypeRegion = apply_filters('utt_post_type_filter', UTTConfig::getPostTypeFilter());

        if (isset($term->taxonomy) && $term->taxonomy == UTTTravelTour::regionTour) {
    ?>
        <div class="ut-widget">
            <h3 class="ut-widget__heading">
                <?php _e('Result type'); ?>
            </h3>
            <div class="valuePreview">
                <?php foreach ($postTypeRegion as  $key => $item) : ?>
                    <a href="<?php echo add_query_arg(array('post_type' => $key)) ?>">
                        <?php echo $item ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    <?php } ?>

    <input type="hidden" name="post_type" value="<?php echo $wp_query->get('post_type') ?>">
    <input type="hidden" name="order" value="<?php echo UTTTravelRequest::getQuery('order', '') ?>">

    <?php if (isset($instance['price']) && $instance['price'] == 'on') : ?>
        <?php
            $priceMin = (isset($_GET['f']['price']['min']) ? number_format($_GET['f']['price']['min'], 0 ,'.','') : $instance['price_min']);
            $priceMax = (isset($_GET['f']['price']['max']) ? number_format($_GET['f']['price']['max'], 0 ,'.','') : $instance['price_max']);
        ?>
        <div class="ut-widget">
            <h3 class="ut-widget__heading">
                <?php _e('Price'); ?>
            </h3>
            <div class="valuePreview">
                <span class="utt-filter-price-from pull-left">
                    <input type="hidden" name="f[price][min]" value="<?php echo $priceMin ?>">
                    <span class="value"></span>
                </span>
                <span class="utt-filter-price-to pull-right">
                    <input type="hidden" name="f[price][max]" value="<?php echo $priceMax ?>">
                    <span class="value"></span>
                </span>
                <div class="clearfix"></div>
            </div>
            <div id="utt_filter_price"
                 data-thousand="<?php echo UTTThousandPoint() ?>"
                 data-dec="<?php echo UTTDecimalPoint() ?>"
                 data-min="<?php echo $instance['price_min'] ?>"
                 data-max="<?php echo $instance['price_max'] ?>"
                 data-steprange="<?php echo $instance['price_step'] ?>"></div>
        </div>
    <?php endif; ?>


    <?php if (isset($instance['departure']) && $instance['departure'] == 'on') : ?>
        <div class="ut-widget">
            <h3 class="ut-widget__heading">
                <?php _e('Departure'); ?>
            </h3>
            <ul class="ut-ul ut-filter__list">
                <?php echo getCheckBoxTerm($regionOptions, 0, 'departure'); ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if (isset($instance['journey']) && $instance['journey'] == 'on') : ?>
        <div class="ut-widget">
            <h3 class="ut-widget__heading">
                <?php _e('Journey'); ?>
            </h3>
            <ul class="ut-ul ut-filter__list">
                <?php echo getCheckBoxTerm($regionOptions, 0, 'journey'); ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if (isset($instance['rating']) && $instance['rating'] == 'on') : ?>
        <?php
        $statusRating = apply_filters('ratings_criteria_tour', array());

        if (count($statusRating) > 0) {
            uttRatinglFilterForm();
        }
        ?>

        <div class="text-right" style="margin-top: 10px;">
            <a href="?reset=true" class="ut-btn ut-btn-link ut-btn-block">Reset filter</a>
        </div>
    <?php endif; ?>

</div>
</form>