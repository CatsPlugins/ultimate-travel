<?php get_header() ?>
    

    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <?php $uttHotel = new utthotel(get_the_ID()) ?>

            <section class="md-section">
                <div class="page-wrap">

                    <div class='ut-container'>
                        <?php UTTFlasSession::output(); ?>
                    </div>
                    <!-- Content-->
                    <div class="md-content">

                        <div class="listing-detail-04 listing-detail-04--02">

                            <?php
                                wp_enqueue_style('swiper');

                                $galeries = $uttHotel->getGalleries();
                                if (count($galeries) > 0) : ?>
                                    <!-- swiper__module swiper-container -->
                                    <div class="swiper__module swiper-container" data-options='{"slidesPerView":1,"slidesPerColumn":1,"spaceBetween":30}'>
                                        <div class="swiper-wrapper">

                                            <?php foreach ($galeries as $key => $image) : ?>
                                                <div>
                                                    <img src="<?php echo $image ?>" alt="<?php the_title() ?>">
                                                </div>
                                            <?php endforeach; ?>

                                        </div>

                                        <div class="swiper-pagination-custom"></div>
                                        <div class="swiper-button-custom">
                                            <div class="swiper-button-prev-custom"></div>
                                            <div class="swiper-button-next-custom"></div>
                                        </div>
                                    </div>
                                    <!-- End / swiper__module swiper-container -->
                            <?php endif; ?>

                            <div class="listing-detail-04__header">
                                <div class="container">

                                    <div class="row">
                                        <div class="col-lg-7 ">
                                            <?php the_title('<h1>', '</h1>'); ?>

                                            <!-- ut-star -->
                                            <?php for($i = 1; $i <=5; $i ++): ?>
                                                <?php if ($i <= $uttHotel->getRatingData()['avg']) : ?>
                                                    <i class="ut-icon-star"></i>
                                                <?php else: ?>
                                                    <i class="ut-icon-star-o"></i>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                            <span style='margin-left: 10px;'>(<?php echo $uttHotel->getRatingData()['total'] ?> <?php _e('review', 'ultimate-travel') ?>)</span>


                                            <div class="listing-detail-04__location">
                                                <?php echo $uttHotel->getBookingData('contact_address') ?>
                                            </div>
                                        </div>

                                        <div class="col-lg-5 ">
                                            <?php wp_enqueue_style('magnific-popup'); ?>
                                            <?php include UTTIncludeTemplatePart('/single/form', 'booking', '/hotel/templates'); ?>
                                        </div>

                                    </div>
                                </div>
                            </div>


                            <div class="listing-detail-04__main">
                                <div class="container">

                                    <div class="row">
                                        <div class="col-md-8 ">


                                            <div class="listing-detail-04__item">
                                                <h2><?php _e('Description', 'ultimate-travel') ?></h2>
                                                <?php the_content() ?>
                                            </div>




                                            <div class="listing-detail-04__item">
                                                <h2><?php _e('Amenities', 'ultimate-travel') ?></h2>
                                                <?php foreach ($uttHotel->getServices() as $service) :   ?>
                                                    <div class="ut-iconbox-01 ut-iconbox-01__style-03">

                                                        <?php if(!empty($service['image_url'])) : ?>
                                                            <div class="ut-iconbox-01__icon">
                                                                <img src="<?php echo $service['image_url']  ?>" alt="<?php echo $service['title']  ?>">
                                                            </div>
                                                        <?php endif; ?>

                                                        <div class="ut-iconbox-01__text"><?php echo $service['title']  ?></div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>





                                            <div class="listing-detail-04__item">
                                                <h2><?php _e('Location', 'ultimate-travel') ?></h2>
                                                <!-- ut-map -->
                                                <div class="ut-map ut-map__style-02" style="height:360px;">
                                                    <div id="mapHotel"
                                                        data-lat="<?php echo $uttHotel->getBookingData('contact_address_lat') ?>"
                                                        data-lng="<?php echo $uttHotel->getBookingData('contact_address_lng') ?>"
                                                        data-zoom="13" data-style="ultra-light"
                                                        style="height: 100%"
                                                     >
                                                    </div>
                                                </div><!-- End / ut-map -->
                                            </div>


                                            <div class="listing-detail-04__item">
                                                <h2><?php _e('Our Rooms', 'ultimate-travel') ?></h2>
                                                <div class="row row-eq-height">
                                                    <?php foreach ($uttHotel->getRoms() as $key => $item) : ?>
                                                        <div class="col-sm-6 ">

                                                            <!-- ut-rooms -->
                                                            <div class="ut-rooms">
                                                                <div class="ut-rooms__media">
                                                                    <img src="<?php echo $item['image_url_full'] ?>" alt="<?php echo $item['title'] ?>">
                                                                </div>
                                                                <div class="ut-rooms__body">
                                                                    <h2 class="ut-rooms__title">
                                                                        <?php echo $item['title'] ?>
                                                                    </h2>
                                                                    <div class="ut-rooms__price">
                                                                        <?php _e('From', 'ultimate-travel') ?>
                                                                        <span>
                                                                            <?php echo UTTCurrencyPosition(number_format($item['price']))  ?>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div><!-- End / ut-rooms -->

                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>


                                            <div class="listing-detail-04__item">
                                                <?php comments_template() ?>
                                            </div>

                                        </div>



                                        <div class="col-md-4 ">

                                            <!-- ut-widget-text__widget -->
                                            <section class="ut-widget-text__widget ut-widget-text__style-02 widget">
                                                <h3 class="widget-title"><?php _e('Contact', 'ultimate-travel') ?></h3>
                                                <div>
                                                    <!-- ut-iconbox-01 -->
                                                    <?php if ($uttHotel->getBookingData('contact_website') != '') : ?>
                                                        <div class="ut-iconbox-01 ut-iconbox-01__style-04">
                                                            <div class="ut-iconbox-01__icon"><i class="ion-android-globe"></i></div>
                                                            <div class="ut-iconbox-01__text"><?php echo $uttHotel->getBookingData('contact_website') ?></div>
                                                        </div><!-- End / ut-iconbox-01 -->
                                                    <?php endif; ?>


                                                    <!-- ut-iconbox-01 -->
                                                    <?php if ($uttHotel->getBookingData('contact_phone') != '') : ?>
                                                        <div class="ut-iconbox-01 ut-iconbox-01__style-04">
                                                            <div class="ut-iconbox-01__icon"><i class="ion-android-call"></i></div>
                                                            <div class="ut-iconbox-01__text"><?php echo $uttHotel->getBookingData('contact_phone') ?></div>
                                                        </div><!-- End / ut-iconbox-01 -->
                                                    <?php endif; ?>

                                                    <!-- ut-iconbox-01 -->
                                                    <?php if ($uttHotel->getBookingData('contact_phone') != '') : ?>
                                                        <div class="ut-iconbox-01 ut-iconbox-01__style-04">
                                                            <div class="ut-iconbox-01__icon"><i class="ion-android-drafts"></i></div>
                                                            <div class="ut-iconbox-01__text"><?php echo $uttHotel->getBookingData('contact_email') ?></div>
                                                        </div><!-- End / ut-iconbox-01 -->
                                                    <?php endif; ?>

                                                    <!-- ut-social -->
                                                    <div class="ut-social ut-social__style-03">
                                                        <span class="ut-social__title"><?php _e('Social Profile', 'ultimate-travel') ?></span>
                                                        <nav class="ut-social__navSocial">
                                                            <?php if ($uttHotel->getBookingData('contact_google') != '') : ?>
                                                                <a class="ut-social__item" href="<?php echo $uttHotel->getBookingData('contact_google') ?>"><i class="fa fa-facebook"></i></a>
                                                            <?php endif; ?>

                                                            <?php if ($uttHotel->getBookingData('contact_facebook') != '') : ?>
                                                                <a class="ut-social__item" href="<?php echo $uttHotel->getBookingData('contact_facebook') ?>"><i class="fa fa-skype"></i></a>
                                                            <?php endif; ?>

                                                            <?php if ($uttHotel->getBookingData('contact_twitter') != '') : ?>
                                                                <a class="ut-social__item" href="<?php echo $uttHotel->getBookingData('contact_twitter') ?>"><i class="fa fa-twitter"></i></a>
                                                            <?php endif; ?>
                                                        </nav>
                                                    </div><!-- End / ut-social -->

                                                </div>
                                            </section><!-- End / ut-widget-text__widget -->


                                            <!-- ut-widget-text__widget -->
                                            <section class="ut-widget-text__widget ut-widget-text__style-02 widget" style="background-color:white;">
                                                <h3 class="widget-title"><?php _e('Gallery', 'ultimate-travel') ?></h3>
                                                <div>

                                                    <!--  -->
                                                    <div class="ut-popup__style-02" data-init="magnificPopup" data-options='{"delegate":"a","type":"image","tLoading":"Loading image #%curr%...","mainClass":"mfp-img-mobile","gallery":{"enabled":true,"navigateByImgClick":true,"preload":[0,1]},"image":{"tError":"<a href=\"%url%\">The image #%curr%</a> could not be loaded."}}' data-effect="mfp-zoom-in">
                                                        <?php
                                                        $galeries = $uttHotel->getGalleries();
                                                        if (count($galeries) > 0) : ?>
                                                            <?php foreach ($galeries as $key => $image) : ?>
                                                                <a href="<?php echo $image ?>" title="<?php the_title() ?>">
                                                                    <img src="<?php echo $image ?>" alt="<?php the_title() ?>"/></a>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </div><!-- End /   -->
                                                </div>
                                            </section><!-- End / ut-widget-text__widget -->

                                            <!-- ut-widget-text__widget -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End / Content-->

                </div>
            </section>
        <?php endwhile; ?>
    <?php endif; ?>
<?php get_footer() ?>