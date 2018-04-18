<?php get_header(); ?>

<div class='ut-container'>

    <?php do_action('utt_before_page') ?>

    <div class="ut-row">

        <?php
            $dataColMain = array(
                'sidebar' => '',
                'primary' => 'ut-col-12',
                'item' => 'ut-col-12 ut-col-sm-6 ut-col-md-4'
            );
            if ( is_active_sidebar( 'hotellist-sidebar' ) ) {
                $dataColMain = array(
                    'sidebar' => 'ut-col-12 ut-col-md-4',
                    'primary' => 'ut-col-12 ut-col-md-8',
                    'item' => 'ut-col-12 ut-col-sm-6'
                );
            }
        ?>

        <?php if ( is_active_sidebar( 'hotellist-sidebar' ) ) : ?>
            <div class="<?php echo $dataColMain['sidebar'] ?>">
                <div class="ut-widgetarea">
                    <?php dynamic_sidebar( 'hotellist-sidebar' ); ?>
                </div>
            </div>
        <?php endif; ?>




        <div class="<?php echo $dataColMain['primary'] ?>">
            <div class="ut-main-content">

                <div class="ut-collection__toolbox ut-widget">
                    <form action="">
                        <?php utt_general_form_request(array('order')); ?>
                        <div class="ut-row">
                            <div class="ut-col-10 ut-col-xl-7 ut-col-lg-10 ut-col-md-80 ut-sort">
                                <div class='ut-row'>
                                    <h2 class='ut-col-12 ut-col-lg-4 ut-hidden--md ut-hidden--sm ut-hidden--xs ut-widget__heading' for="">Sort by: </h2>
                                    <div class='ut-col-12 ut-col-lg-8'>
                                        <select name="order" onchange="form.submit()" class="ut-form__control">
                                            <?php utt_options_sort('order','hotel') ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class='ut-collection__products-container <?php echo UTTTravelRequest::getQuery('layout') ?>'>

                    <?php if (have_posts()): ?>
                        <div class="ut-row">
                            <?php while (have_posts()): the_post(); ?>
                                <div class="<?php echo $dataColMain['item'] ?> ut-collection__product-col">
                                    <?php UTTLoadTemplatePart('/loop/list-loop', 'content', '/hotel/templates'); ?>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <?php UTTLoadTemplatePart('/notfound', 'hotel', '/hotel/templates'); ?>
                    <?php endif; ?>


                    <?php UTTFrontend::pagination(); ?>
                </div>

            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
