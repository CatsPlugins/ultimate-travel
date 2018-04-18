<?php get_header() ?>

    <div class='ut-container ut-product--car'>
        <?php UTTFlasSession::output(); ?>

        <?php do_action('utt_before_page_car') ?>

        <?php if (have_posts()) : ?>

            <?php while(have_posts()): the_post();
                $metaData = get_post_meta(get_the_ID(), UTTTravelCar::keyMeta, true);

                $_carGalleries= get_post_meta(get_the_ID(), UTTTravelCar::keyMetaGallery, true);
                $_carGalleries = explode(',', $_carGalleries);

                $carGalleries = array();
                foreach ($_carGalleries as $key => $value) {
                    $carGalleries[] = wp_get_attachment_image_src($value, UTTConfig::SIZE_BIG)[0];
                }

                $trademarkCar = wp_get_post_terms( get_the_ID(), UTTTravelCar::trademark, array());
                $dateCar = wp_get_post_terms( get_the_ID(), UTTTravelCar::date, array());

            ?>
                <div class="ut-row">
                    <div class='ut-col-12 <?php echo (get_option('utt_booking_car', '') == 'on' ? 'ut-col-md-8 ut-col-xl-9' : '') ?>'>
                        <div class='ut-row'>
                            <div class='ut-col-12 ut-col-sm-6'>
                                <div class=" ut-product__image">
                                    <div class='ut-product__main-image'>
                                        <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), UTTConfig::SIZE_BIG) ?>" alt="<?php the_title() ?>">
                                    </div>
                                    <div class='owl-carousel owl-theme ut-product__image-list'>
                                        <div class='ut-product__image-list__item'>
                                            <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), UTTConfig::SIZE_BIG) ?>" alt="<?php the_title() ?>">
                                        </div>
                                        <?php if(is_array($carGalleries) && count($carGalleries) > 0) : ?>
                                            <?php foreach ($carGalleries as $item) : ?>
                                                <div class='ut-product__image-list__item'>
                                                    <img src="<?php echo $item ?>" alt="<?php the_title() ?>">
                                                </div>
                                            <?php endforeach; ?>
                                        <?php endif ?>

                                    </div>
                                </div>
                            </div>

                            <div class='ut-col-12 ut-col-sm-6'>
                                <div class="ut-product__content">
                                    <div class='ut-product__content-group'>
                                        <div class='ut-product__heading'>
                                            <h1 class="ut-product__title" title='<?php the_title() ?>'>
                                                <?php the_title() ?>
                                            </h1>
                                            <div class='ut-product__price-group'>
                                            <span>
                                                <?php _e('Daily rent:', 'ultimate-travel') ?>
                                            </span>
                                                <ins class='ut-product__price'>
                                                    <?php echo isset($metaData['day_rent']) ? $metaData['day_rent'] : 0 ?>
                                                </ins>
                                            </div>
                                        </div>
                                        <hr/>
                                        <div class="ut-row ut-product__section">
                                            <div class="ut-list-icon ut-col-6 ut-product__detail__item">
                                                <ul class='ut-product__detail-list'>
                                                    <li>
                                                        <strong><?php _e('Car company:', 'ultimate-travel') ?> </strong>
                                                        <?php if (!is_wp_error($trademarkCar)) {
                                                            foreach ($trademarkCar as $item) {
                                                                $link = get_term_link($item);
                                                                echo "<a href='{$link}' title='{$item->name}'>{$item->name}</a>";
                                                            }
                                                        } ?>
                                                    </li>
                                                    <li>
                                                        <strong><?php _e('Date car:', 'ultimate-travel') ?> </strong>
                                                        <?php if (!is_wp_error($dateCar)) {
                                                            foreach ($dateCar as $item) {
                                                                $link = get_term_link($item);
                                                                echo "<a href='{$link}' title='{$item->name}'>{$item->name}</a>";
                                                            }
                                                        } ?>
                                                    </li>
                                                    <li><strong><?php _e('Color the car:', 'ultimate-travel') ?> </strong> <?php echo (isset($metaData['color']) ? $metaData['color'] : '') ?></li>
                                                    <li><strong><?php _e('Day rent:', 'ultimate-travel') ?> </strong> <?php echo (isset($metaData['day_rent']) ? $metaData['day_rent'] : 0) ?></li>
                                                </ul>
                                            </div>
                                            <div class="ut-list-icon ut-col-6 ut-product__detail__item">
                                                <ul class='ut-product__detail-list'>
                                                    <li>
                                                        <strong><?php _e('SKU:', 'ultimate-travel') ?> </strong>
                                                        <?php echo isset($metaData['car_code']) ? $metaData['car_code'] : '' ?>
                                                    </li>
                                                    <li>
                                                        <strong><?php _e('Extra hours:', 'ultimate-travel') ?> </strong>
                                                        <?php echo UTTCurrencyPosition(UTTCurrencyFormat(isset($metaData['extra_hours']) ? $metaData['extra_hours'] : 0 )) ?>
                                                    </li>
                                                    <li>
                                                        <strong><?php _e('Extra km:', 'ultimate-travel') ?> </strong>
                                                        <?php echo UTTCurrencyPosition(isset($metaData['extra_km']) ? $metaData['extra_km'] : 0 ) ?>

                                                    </li>

                                                    <li>
                                                        <strong><?php _e('Monthly rent:', 'ultimate-travel') ?> </strong>
                                                        <?php echo UTTCurrencyPosition(isset($metaData['monthly_rent']) ? $metaData['monthly_rent'] : 0 ) ?>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <hr/>
                                    <div class="ut-product__share-button ut-product__section">
                                        <?php $url = get_permalink(); ?>
                                        <a href="javascript:;" onclick="window.open('//facebook.com/sharer/sharer.php?u=<?php echo $url ?>')"><img src="<?php echo plugin_dir_url(UTT_PATH) ?>/asset/frontend/images/share-fb.png" alt="<?php _e('Share on Facebook', 'ultimate-travel') ?>"></a>
                                        <a href="javascript:;" onclick="window.open('https://plus.google.com/share?url=<?php echo $url ?>')"><img src="<?php echo plugin_dir_url(UTT_PATH) ?>/asset/frontend/images/share-google.png" alt="<?php _e('Share on Google', 'ultimate-travel') ?>"></a>
                                        <a href="javascript:;" onclick="window.open('https://twitter.com/home?status=<?php echo $url ?>')"><img src="<?php echo plugin_dir_url(UTT_PATH) ?>/asset/frontend/images/share-tw.png" alt="<?php _e('Share on Twitter', 'ultimate-travel') ?>"></a>
                                    </div>
                                </div>
                            </div>
                            <div class='ut-col-12'>
                                <div class='ut-product__description'>
                                    <?php the_content() ?>
                                </div>

                                <div class="listing-detail-04__item">
                                    <?php comments_template() ?>
                                </div>

                            </div>
                        </div>
                    </div>

                    <?php if (get_option('utt_booking_car', '') == 'on')  : ?>
                        <div class='ut-col-12 ut-col-md-4 ut-col-xl-3 ut-product-sidebar'>
                            <div class="ut-product-sidebar__content">
                            <?php if (get_option('utt_booking_car_form') != ''): ?>
                                <?php echo get_option('utt_booking_car_form') ?>
                            <?php else : ?>
                                <?php include UTTIncludeTemplatePart('single/form', 'booking', '/car/templates/') ?>
                            <?php endif ?>
                            </div>
                        </div>
                    <?php else: ?>

                    <?php endif; ?>


                </div>
            <?php endwhile; ?>

        <?php else: ?>
            <div class="ut-alert ut-alert-danger"><?php _e('Car not found', 'ultimate-travel') ?></div>
        <?php endif; ?>
    </div>

<?php get_footer() ?>