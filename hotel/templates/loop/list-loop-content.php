<?php $uttHotel = new utthotel(get_the_ID()); ?>
<div class="post-04">
    <div class="post-04__media">
        <a href="<?php the_permalink() ?>" title="<?php the_title() ?>">
            <?php if (has_post_thumbnail()): ?>
                <?php the_post_thumbnail(UTTConfig::SIZE_THUMBNAIL) ?>
            <?php else: ?>
                <img src="<?php echo plugin_dir_url(UTT_PATH) ?>asset/frontend/images/no-image.png" alt="<?php the_title() ?>">
            <?php endif; ?>
        </a>

        <div class="star">
            <!-- ut-star -->
            <?php for($i = 1; $i <=5; $i ++): ?>
                <?php if ($i <= $uttHotel->getRatingData()['avg']) : ?>
                    <i class="ut-icon-star"></i>
                <?php else: ?>
                    <i class="ut-icon-star-o"></i>
                <?php endif; ?>
            <?php endfor; ?>
            <span style='margin-left: 10px;'>(<?php echo $uttHotel->getRatingData()['total'] ?> <?php _e('review', 'ultimate-travel') ?>)</span>
        </div>

    </div>
    <div class="post-04__body">
        <h2 class="post-04__title">
            <a href="<?php the_permalink() ?>" title="<?php the_title() ?>"><?php the_title() ?></a>
        </h2>
        <div class="post-04__location">
            <?php echo $uttHotel->getBookingData('contact_address') ?>
        </div>
    </div>
</div>

