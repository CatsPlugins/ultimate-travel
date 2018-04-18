<h2 class="ut-product-sidebar__heading" style="margin-left: -15px; margin-right: -15px;">
    <?php _e('BUY ONLINE', 'ultimate-travel') ?>
</h2>

<div class="ut-form ut-sidebar__form">
    <form action="" method="post">
        <?php wp_nonce_field( 'form_booking', 'utt_car_booking', get_the_permalink(), true) ?>
        <input type="hidden" name="databookingcar[id]" id="" value="<?php echo get_the_ID() ?>">
        <div class="ut-form__group">
            <label class="ut-form__label"><?php _e('Full name *', 'ultimate-travel') ?></label>
            <input required class="ut-form__control" name="databookingcar[name]" type="text">
        </div>
        <div class="ut-form__group">
            <label class="ut-form__label"><?php _e('Email *', 'ultimate-travel') ?></label>
            <input required class="ut-form__control" name="databookingcar[email]" type="text">
        </div>
        <div class="ut-form__group">
            <label class="ut-form__label"><?php _e('Phone *', 'ultimate-travel') ?></label>
            <input required class="ut-form__control" name="databookingcar[phone]" type="text">
        </div>
        <div class="ut-form__group">
            <div class="ut-row">
                <div class="ut-col-12 ut-col-sm-6">
                    <label class="ut-form__label"><?php _e('Get car *', 'ultimate-travel') ?></label>
                    <input required id="get-date" name="databookingcar[form_date]" class="ut-form__control" type="text">
                </div>
                <div class="ut-col-12 ut-col-sm-6">
                    <label class="ut-form__label"><?php _e('Return car *', 'ultimate-travel') ?></label>
                    <input required id="return-date" name="databookingcar[to_date]" class="ut-form__control" type="text">
                </div>
            </div>
        </div>
        <div class="ut-form__group">
            <label class="ut-form__label"><?php _e('Point of departure *', 'ultimate-travel') ?></label>
            <input class="ut-form__control" required name="databookingcar[point_start]" type="text">
        </div>
        <div class="ut-form__group">
            <label class="ut-form__label"><?php _e('Destination *', 'ultimate-travel') ?></label>
            <input class="ut-form__control" required name="databookingcar[destination]" type="text">
        </div>
        <div class="ut-form__group">
            <div class="ut-row">
                <div class="ut-col-12 ut-col-sm-6">
                    <label class="ut-checkbox-group">
                        <input type="checkbox" name="databookingcar[one-way]" value="yes">
                        <strong>
                            <?php _e('One-way') ?>
                        </strong>
                    </label>
                </div>
                <div class="ut-col-12 ut-col-sm-6">
                    <label class="ut-checkbox-group">
                        <input type="checkbox" value="yes" name="databookingcar[round-trip]">
                        <strong>
                            <?php _e('Round-trip', 'ultimate-travel') ?>
                        </strong>
                    </label>
                </div>
            </div>
        </div>
        <div class="ut-text--center">
            <button class="ut-btn" type="submit"><?php _e('BOOK NOW', 'ultimate-travel') ?></button>
        </div>
    </form>
</div>