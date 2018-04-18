<?php get_header() ?>
    <div class='ut-container'>

        <?php do_action('utt_before_page_car') ?>

        <div class="ut-row">
            <?php if ( is_active_sidebar( 'carlist-sidebar' ) ) : ?>
                <div class="ut-col-12 ut-col-md-4">
                    <div class="ut-widgetarea">
                        <?php dynamic_sidebar( 'carlist-sidebar' ); ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php
                $classPrimaryCol = 'ut-col-12';
                $classItemLoop = 'ut-col-12 ut-col-sm-4';
                if (is_active_sidebar( 'carlist-sidebar' )) {
                    $classPrimaryCol = 'ut-col-12 ut-col-md-8';
                    $classItemLoop = 'ut-col-12 ut-col-sm-6';
                }
            ?>

            <div class="<?php echo $classPrimaryCol ?>">
                <div class="ut-main-content">
                    <form action="">
                        <?php utt_general_form_request(array('order')); ?>
                        <div class="ut-collection__toolbox ut-widget">
                            <div class="ut-row">
                                <div class="ut-col-10 ut-col-xl-7 ut-col-lg-10 ut-col-md-80 ut-sort">
                                    <div class='ut-row'>
                                        <h2 class='ut-col-12 ut-col-lg-4 ut-hidden--md ut-hidden--sm ut-hidden--xs ut-widget__heading' for=""><?php _e('Sort by:', 'ultimate-travel') ?> </h2>
                                        <div class='ut-col-12 ut-col-lg-8'>
                                            <div class='ut-row'>
                                                <div class='ut-form__group ut-col-12 ut-col-sm-6 ut-nomargin'>
                                                    <select name="order" onchange="form.submit()" class="ut-form__control">
                                                        <?php utt_options_sort('order','car') ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="ut-col-2 ut-col-xl-5 ut-col-lg-2 ut-col-md-20 ut-view-mode__container">
                                    <ul class="ut-ul ut-view-mode__list">
                                        <li class="ut-view-mode__item <?php echo UTTTravelRequest::getQuery('layout') == 'grid' ? 'active' : '' ?>">
                                            <a href="<?php echo add_query_arg(array('layout'=>'grid')) ?>" class="<?php echo UTTTravelRequest::getQuery('layout') == 'grid' ? 'active' : '' ?>">
                                                <i class="ut-icon-thumbnails"></i>
                                            </a>
                                        </li>
                                        <li class="ut-view-mode__item <?php echo UTTTravelRequest::getQuery('layout') == 'list' ? 'active' : '' ?>">
                                            <a href="<?php echo add_query_arg(array('layout'=>'list')) ?>" class="<?php echo UTTTravelRequest::getQuery('layout') == 'list' ? 'active' : '' ?>">
                                                <i class="ut-icon-list-bullet"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class='ut-collection__products-container <?php echo UTTTravelRequest::getQuery('layout') ?>'>

                        <?php if (have_posts()): ?>
                            <div class="ut-row">
                                <?php while (have_posts()): the_post(); ?>
                                    <div class="<?php echo $classItemLoop ?> ut-collection__product-col">
                                        <?php UTTLoadTemplatePart('loop/list-loop', 'content', '/car/templates/'); ?>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php else: ?>
                            <?php UTTLoadTemplatePart('notfound', 'tour'); ?>
                        <?php endif; ?>

                        <?php UTTFrontend::pagination(); ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- filter scroll control -->
        <div class="ut-filter__control-mobile ut-hidden--xl ut-hidden--lg ut-hidden--md text-center">
            <a id='ut-filter-show' class='ut-text--center'><i class="ut-icon-filter"></i> <?php _e('Filter', 'ultimate-travel') ?></a>
            <a id='ut-filter-cancel' class='ut-btn ut-hidden'><?php _e('Cancel', 'ultimate-travel') ?></a>
            <a id='ut-filter-apply' class='ut-btn ut-hidden'><?php _e('Apply', 'ultimate-travel') ?></a>
        </div>
    </div>
<?php get_footer() ?>