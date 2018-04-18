<?php
$trademarkCar = wp_get_post_terms( get_the_ID(), UTTTravelCar::trademark, array());
$seatCar = wp_get_post_terms( get_the_ID(), UTTTravelCar::seats, array());

?>
<div class="ut-product ut-product--item ut-product--car">
    <div class="ut-product__image text-center">
        <a href="<?php the_permalink() ?>" title="<?php the_title() ?>">
            <?php if (has_post_thumbnail()): ?>
                <?php the_post_thumbnail(UTTConfig::SIZE_THUMBNAIL) ?>
            <?php else: ?>
                <img src="<?php echo plugin_dir_url(UTT_PATH) ?>asset/frontend/images/no-image.png" alt="<?php the_title() ?>">
            <?php endif; ?>
        </a>
    </div>
    <div class="ut-product__content">
        <div class='ut-product__heading'>
            <h3 class="ut-product__title" title='Chevrolet Captiva 07 seats'>
                <a href="<?php the_permalink() ?>" title="<?php the_title() ?>"><?php the_title() ?></a>
            </h3>
            <div class='ut-product__price-group'>
                <ins class='ut-product__price'>
                    <?php echo UTTConfig::beforePrice() ?><?php echo UTTCurrencyFormat(get_post_meta(get_the_ID(), UTTTravelCar::keyMetaPrice, true)) ?> <?php echo UTTConfig::afterPrice() ?>
                </ins>
            </div>
        </div>
        <div class="ut-product__detail ut-row">
            <div class='ut-product__vendor ut-col ut-text--center'>
                <i class="ut-icon-car"></i>
                <?php
                    if (count($trademarkCar) > 0) {
                        echo $trademarkCar[0]->name;
                    }
                ?>
            </div>
            <div class="ut-product__capacity ut-col ut-text--center">
                <i class='ut-icon-calendar'></i>
                <?php

                if (count($seatCar) > 0) {
                    echo $seatCar[0]->name;
                }
                ?>
                seats
            </div>
        </div>

        <div class="ut-product__rating">
            <span>
                <?php echo UTTConfig::beforePrice() ?><?php echo UTTCurrencyFormat(get_post_meta(get_the_ID(), UTTTravelCar::keyMetaPriceMonth, true)) ?><?php echo UTTConfig::afterPrice() ?>
                <?php _e('/ Month', 'ultimate-travel') ?>
            </span>
        </div>
    </div>
</div>