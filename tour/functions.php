<?php

if(!function_exists('UTTGetPriceHtmlTour')) {
    function UTTGetPriceHtmlTour($id)
    {
        $priceTour = get_post_meta($id, '_regular_price', true);
        $priceSaleTour = get_post_meta($id, '_sale_price', true);
        $price = array(0);
        if ($priceSaleTour > 0) {
            $price[0] = (int)$priceSaleTour;
            $price[1] = (int)$priceTour;
        } else {
            $price[0] = ($priceTour != '' ? $priceTour : 0);
        }


        if ($price[0] == 0) {
            $price[0] = apply_filters('text_price_free', 'Contact');
        } else {
            $price[0] = UTTCurrencyPosition(UTTCurrencyFormat($price[0]));
        }

        ob_start();
        ?>
        <?php if (isset($price[1]) && $price[1] > 0): ?>
            <del class='ut-product__compare-price'>
                <?php echo UTTCurrencyPosition(UTTCurrencyFormat($price[1])) ?>
            </del>
        <?php endif; ?>

        <ins class='ut-product__price'>
            <?php echo $price[0] ?>
        </ins>
    <?php

        return ob_get_clean();
    }
}

if(!function_exists('UTTGetRatingTour')) {
    function UTTGetRatingTour($id) {
        $totalTourRating = get_post_meta($id, 'cats_total_rating', true);
        $avgTourRating = get_post_meta($id, 'cats_avg_rating', true);

        ob_start();
        ?>
        <div class="ut-product__rating">
            <?php for($i = 1; $i <=5; $i ++): ?>
                <?php if ($i <= $avgTourRating) : ?>
                    <i class="ut-icon-star"></i>
                <?php else: ?>
                    <i class="ut-icon-star-o"></i>
                <?php endif; ?>
            <?php endfor; ?>
            <span style='margin-left: 10px;'>(<?php echo $totalTourRating ?> review)</span>
            </div>
        <?php
        return ob_get_clean();
    }
}


if(!function_exists('uttRengerJourneySort')) {
    function uttRengerJourneySort($post_id, $name) {
        $meta = get_post_meta($post_id, '_journey', true);
        $html = '<ul class="UTTorderList">';

        if(is_array($meta) && count($meta) > 0) {
            foreach ($meta as $item) {
                $term = get_term($item);
                $label = $term->name;
                $html .= '<li><input name="'. $name .'" value="'. $item .'" type="hidden">'.$label.'</li>';
            }
        }

        $html .= '</ul>';

        return $html;
    }
}