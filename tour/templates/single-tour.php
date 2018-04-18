<?php get_header(); ?>

<?php if (have_posts()): ?>
    <?php
        while (have_posts()): the_post();
            $tourDetail = new uttour(get_the_ID());
            $attributesSelected = get_post_meta(get_the_ID(), 'attributesSelected', true);
            $tourGalleries = $tourDetail->getGalleries();
        ?>

        <div class='ut-container'>
            <?php UTTFlasSession::output(); ?>

            <?php do_action('utt_before_page_tour') ?>

            <div class="ut-product__row ut-product--tour">

                <div class=" ut-product__image">
                    <div class='ut-product__main-image'>
                        <?php if (has_post_thumbnail()) : ?>
                            <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), UTTConfig::SIZE_BIG) ?>" alt="<?php the_title() ?>">
                        <?php elseif(isset($tourGalleries[0])) : ?>
                            <img src="<?php echo $tourGalleries[0] ?>" alt="<?php the_title() ?>">
                        <?php else : ?>
                            <img src="<?php echo plugin_dir_url(UTT_PATH) ?>asset/frontend/images/no-image.png" alt="<?php the_title() ?>">
                        <?php endif; ?>
                    </div>
                    <div class='owl-carousel owl-theme ut-product__image-list'>

                        <?php if (has_post_thumbnail()) : ?>
                            <div class='ut-product__image-list__item'>
                                <img src='<?php echo get_the_post_thumbnail_url(get_the_ID(), UTTConfig::SIZE_BIG) ?>'/>
                            </div>
                        <?php endif; ?>

                        <?php foreach ($tourGalleries as $image) : ?>
                            <div class='ut-product__image-list__item'>
                                <img src='<?php echo $image ?>'/>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="ut-product__content">
                    <div class='ut-product__content-group'>
                        <div class='ut-product__heading'>
                            <h1 class="ut-product__title" title='<?php the_title() ?>'>
                                <?php the_title() ?>
                            </h1>
                            <div class='ut-product__price-group'>
                                <?php echo UTTGetPriceHtmlTour(get_the_ID()) ?>
                            </div>
                        </div>

                        <?php if ($tourDetail->checkLastMinute()) : ?>
                            <span class="lastminutes">
                                <?php _e('Last Minute', 'ultimate-travel') ?>
                            </span>
                            <i><?php echo $tourDetail->checkLastMinute(); ?></i>
                        <?php endif; ?>

                        <div class="ut-row ut-product__section">

                            <div class="ut-col-12 ut-col-xl-6">
                                <div class="ut-product__journey">
                                    <?php _e('Journey:') ?> <b>

                                        <?php
                                            $journey = $tourDetail->getRegions();
                                            $regionByTour = array();
                                            if (is_array($journey) && count($journey) > 0) {
                                                foreach ($journey as $item) {
                                                    $link = get_term_link($item->term_id);
                                                    $link = add_query_arg('post_type', $tourDetail->postType, $link );
                                                    $link = esc_url($link);
                                                    $regionByTour[] = "<a href=\"{$link}\" title='{$item->name}'>{$item->name}</a>";
                                                }

                                            }
                                            echo implode( ' - ', $regionByTour);
                                        ?>
                                    </b>
                                </div>
                            </div>

                            <div class="ut-col-12 ut-col-xl-6">
                                <div class="ut-product__share-button">
                                    <?php $url = get_permalink(); ?>
                                    <a href="javascript:;" onclick="window.open('//facebook.com/sharer/sharer.php?u=<?php echo $url ?>')"><img src="<?php echo plugin_dir_url(UTT_PATH) ?>/asset/frontend/images/share-fb.png" alt="<?php _e('Share on Facebook', 'ultimate-travel') ?>"></a>
                                    <a href="javascript:;" onclick="window.open('https://plus.google.com/share?url=<?php echo $url ?>')"><img src="<?php echo plugin_dir_url(UTT_PATH) ?>/asset/frontend/images/share-google.png" alt="<?php _e('Share on Google', 'ultimate-travel') ?>"></a>
                                    <a href="javascript:;" onclick="window.open('https://twitter.com/home?status=<?php echo $url ?>')"><img src="<?php echo plugin_dir_url(UTT_PATH) ?>/asset/frontend/images/share-tw.png" alt="<?php _e('Share on Twitter', 'ultimate-travel') ?>"></a>
                                </div>
                            </div>

                        </div>
                        <hr />
                        <div class="ut-row ut-product__section">

                            <?php if ($tourDetail->getMeta('text_feature') != '') : ?>
                                <div class="ut-col-6">
                                    <div class='ut-list-icon'>
                                        <i class="ut-icon-star"></i>
                                        <span><?php echo $tourDetail->getMeta('text_feature') ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="ut-col-6">
                                <div class="ut-product__rating">
                                    <?php for($i = 1; $i <=5; $i ++): ?>
                                        <?php if ($i <= $tourDetail->getRatingData()['avg']) : ?>
                                            <i class="ut-icon-star"></i>
                                        <?php else: ?>
                                            <i class="ut-icon-star-o"></i>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                    <span style='margin-left: 10px;'>(<?php echo $tourDetail->getRatingData()['total'] ?> <?php _e('review', 'ultimate-travel') ?>)</span>
                                </div>
                            </div>

                        </div>

                        <hr />
                        <div class="ut-row ut-product__section ut-product__detail">
                            <?php if ($tourDetail->getMeta('sku') != '') : ?>
                                <div class="ut-list-icon ut-col-6 ut-product__detail__item">
                                    <i class='ut-icon-barcode'></i>
                                    <span><?php _e('Tour code:', 'ultimate-travel') ?> <?php echo $tourDetail->getMeta('sku') ?></span>
                                </div>
                            <?php endif; ?>

                            <?php if ($tourDetail->getMeta('rest') != '') : ?>
                                <div class="ut-list-icon ut-col-6 ut-product__detail__item">
                                    <i class='ut-icon-barcode'></i>
                                    <span><?php _e('The rest:', 'ultimate-travel') ?> <?php echo $tourDetail->getMeta('rest') ?></span>
                                </div>
                            <?php endif; ?>

                            <?php if ($tourDetail->getMeta('customertype') != '') : ?>
                                <div class="ut-list-icon ut-col-6 ut-product__detail__item">
                                    <i class='ut-icon-barcode'></i>
                                    <span><?php _e('Customer Type:', 'ultimate-travel') ?> <?php echo $tourDetail->getMeta('customertype') ?></span>
                                </div>
                            <?php endif; ?>

                            <?php if ($tourDetail->getMeta('time') != '') : ?>
                                <div class="ut-list-icon ut-col-6 ut-product__detail__item">
                                    <i class='ut-icon-barcode'></i>
                                    <span><?php _e('Time:', 'ultimate-travel') ?> <?php echo $tourDetail->getMeta('time') ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <hr class='ut-hidden--sm ut-hidden--xs ut-hidden-md'/>

                    </div>


                    <?php UTTLoadTemplatePart('single/form', 'booking') ?>

                </div>
            </div>

            <hr />

            <div class="ut-product__section ut-product__services">
                <h2 class='h5'><?php _e('Sevices Attached:') ?> </h2>
                <div class="ut-row ut-text--center">
                    <?php foreach($attributesSelected as $tax): ?>
                        <?php foreach($tax as $term) : ?>
                            <?php
                            $term = get_term($term);
                            $image = UTTGetImageTerm($term->term_id, UTTTravelTour::$keyTermIcon);
                            if (!empty($image)) {
                                $image = "<img src=\"{$image}\"/>";
                            }
                            ?>
                            <div class="ut-col ut-col-md-4 ut-col-lg-2">
                                <div class="ut-box ut-product__services__item">
                                    <?php echo $image; ?>
                                    <?php echo $term->name ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>

        <div class='ut-product-navbar-wrap'>
            <div class='ut-product-navbar navbar' id='myNavbar' data-gumshoe-header>
                <div class='ut-container'>
                    <div class='ut-row'>
                        <ul class='ut-col-12 ut-menu ut-menu--horizontal nav navbar-nav' data-gumshoe>
                            <li class="ut-menu__item">
                                <a href='#section1' class="ut-text--center"><?php _e('Itinerary', 'ultimate-travel') ?></a>
                            </li>
                            <li class="ut-menu__item">
                                <a href='#section3' class="ut-text--center"><?php _e('Reviews', 'ultimate-travel') ?></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class='ut-container'>
            <div class='ut-row'>

                <?php if ( is_active_sidebar( 'tourdetail-sidebar' ) ) : ?>
                    <div class='ut-col-12 ut-col-sm-70 ut-col-lg-8 ut-col-xl-9 ut-product__bottom'>
                <?php else: ?>
                    <div class='ut-col-12 ut-col-sm-70 ut-col-lg-12 ut-col-xl-12 ut-product__bottom'>
                <?php endif; ?>
                    <div id="section1" class='ut-product__section ut-product__itinerary'>

                        <h2 class='h5'>
                            <i class='ut-icon-car'></i> <?php _e('Itinerary', 'ultimate-travel') ?>
                        </h2>
                        <div class='ut-product__itinerary__content'>
                            <?php foreach ($tourDetail->getTourContent() as $item): ?>
                                <h3 class='ut-product__itinerary__title'>
                                    <?php
                                        if(isset($item['icon'])) {
                                            echo "<i class='{$item['icon']}'></i>";
                                        }
                                    ?>
                                    <?php echo $item['title'] ?>
                                    <small><?php echo apply_filters('the_content', $item['desc']) ?></small>
                                </h3>


                                <?php echo apply_filters('the_content', str_replace('__dot__', '</div><div class="ut-product__itinerary__section">', '<div class="ut-product__itinerary__section">' . $item['content'] . '</div>')) ?>

                            <?php endforeach; ?>
                        </div>
                    </div>

                    <?php UTTLoadTemplatePart('single/related', 'tour') ?>

                    <div id="section3" class='ut-product__section ut-product__comment'>
                        <?php include UTTIncludeTemplatePart('single/review', 'rating'); ?>
                    </div>
                </div>


                <?php if ( is_active_sidebar( 'tourdetail-sidebar' ) ) : ?>
                    <div class='ut-col-12 ut-col-sm-30 ut-col-lg-4 ut-col-xl-3 ut-product-sidebar'>
                        <div class="ut-widgetarea">
                            <?php dynamic_sidebar( 'tourdetail-sidebar' ); ?>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </div>

    <?php endwhile; ?>
<?php endif; ?>

<?php get_footer(); ?>
