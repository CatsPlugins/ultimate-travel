<?php
$uttour = new uttour(get_the_ID());

$flasSale = '';
if ($uttour->getPriceAdults('price_origin') > 0) {
    $flasSale = __('<span class="ut-product__sale-tag">SALE</span>', 'ultimate-travel');
}

?>

<div class="ut-product ut-product--item">
    <div class="ut-product__image">
        <a href="<?php the_permalink() ?>" title="<?php the_title() ?>">
            <?php if (has_post_thumbnail()): ?>
                <?php the_post_thumbnail(UTTConfig::SIZE_THUMBNAIL) ?>
            <?php else: ?>
                <img src="<?php echo plugin_dir_url(UTT_PATH) ?>asset/frontend/images/no-image.png" alt="<?php the_title() ?>">
            <?php endif; ?>
        </a>

        <?php echo $flasSale ?>

    </div>
    <div class="ut-product__content">
        <div class='ut-product__heading'>
            <h3 class="ut-product__title" title='<?php the_title() ?>'>
                <a href='<?php the_permalink() ?>'><?php echo uttNiceWord(get_the_title(), 60) ?></a>
            </h3>
            <div class='ut-product__price-group'>
                <?php echo $uttour->getPriceHtml() ?>
            </div>
        </div>
        <div class="ut-product__detail">
            <div class='ut-product__time'>
                <div class="ut-product__duration">
                    <i class='ut-icon-clock-o'></i> <?php echo $uttour->getMeta('time'); ?>
                </div>
                <?php if ($uttour->getScheduleRecent() != '') : ?>
                    <div class="ut-product__availability">
                        <i class='ut-icon-calendar'></i>
                        <?php echo $uttour->getScheduleRecent(); ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class='ut-product__itinerary'>
            </div>

        </div>
        <div class="ut-product__description ut-hidden--md">
            <?php echo uttNiceWord(get_the_content(), 80) ?>
        </div>

        <?php echo UTTGetRatingTour(get_the_ID()); ?>
    </div>
</div>