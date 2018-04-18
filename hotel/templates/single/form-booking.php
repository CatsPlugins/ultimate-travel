<?php

if (get_option('utt_booking_hotel', '') != 'on') {
    return false;
}

if (get_option('utt_booking_hotel_form', '') != '') {
    echo html_entity_decode(get_option('utt_booking_hotel_form', ''));

    return false;
}

?>
<!-- btn btn-primary btn-w170 -->
<a class="btn btn-primary btn-w170" style="margin-bottom:40px;" href="#popup_01" data-init="magnificPopup" data-options='{"type":"inline","removalDelay":700}' data-effect="mfp-zoom-in">
    <?php _e('Booking now', 'ultimate-travel') ?>
</a><!-- End / btn btn-primary btn-w170 -->


<!-- ut-popup-content mfp-with-anim mfp-hide -->
<div class="ut-popup-content mfp-with-anim mfp-hide" id="popup_01">
    <h2><?php _e('Booking now', 'ultimate-travel') ?></h2>
    <p class="popup_desc"><?php _e('* Is this a required field.', 'ultimate-travel') ?></p>
    <div class="form-01">

        <form class="form-01__form" method="post">
            <?php wp_nonce_field( 'form_booking_hotel', 'utt_hotel_booking', false, true) ?>
            <input type="hidden" value="<?php echo get_the_ID() ?>" name="id">
            <input type="hidden" value="<?php echo get_permalink() ?>" name="redirectUrl">
            <div class="row">
                <div class="col-sm-4">
                    <input class="" required type="text" name="name" placeholder="<?php _e('Full name *', 'ultimate-travel') ?>"/>
                </div>
                <div class="col-sm-4">
                    <input class="" type="tel" name="phone" placeholder="<?php _e('Phone', 'ultimate-travel') ?>"/>
                </div>
                <div class="col-sm-4">
                    <input class="" required type="email" name="email" placeholder="<?php _e('Email *', 'ultimate-travel') ?>"/>
                </div>

                <div class="col-sm-6 ">
                    <!--  -->
                    <div data-jqueryUi="datepicker">
                        <input class="ui-datepicker_show" required type="text" name="start_date" placeholder="Set Date *" value="<?php echo date('d/m/Y H:i') ?>"/>
                    </div><!-- End /  -->

                </div>
                <div class="col-sm-6 ">

                    <!--  -->
                    <div data-jqueryUi="datepicker">
                        <input class="ui-datepicker_show" required type="text" name="end_date" placeholder="Set Date *"/>
                    </div><!-- End /  -->

                </div>
                <div class="col-lg-4 ">
                    <input type="number" name="number_adults" required placeholder="Adults" value="1">
                </div>
                <div class="col-lg-4 ">
                    <input type="number" name="number_kid" placeholder="Kid">
                </div>
                <div class="col-lg-4 ">
                    <select required class="custom-select" name="room_name">
                        <option value="">Room</option>

                        <?php foreach ($uttHotel->getRoms() as $key => $item) : ?>
                            <option value="<?php echo $item['title'] ?>">
                                <?php echo $item['title'] ?> (<?php echo UTTCurrencyPosition(number_format($item['price']))  ?>)
                            </option>
                        <?php endforeach; ?>

                    </select>
                </div>
                <div class="col-lg-12 ">
                    <textarea name="note" rows="5" placeholder="<?php _e('Note...', 'ultimate-travel') ?>"></textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-w140"><?php _e('Book now', 'ultimate-travel') ?></button>
        </form>

    </div>
</div>
