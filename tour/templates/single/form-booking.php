<?php
global $uttour;

if (get_option('utt_booking_tour', '') != 'on') {
    return false;
}

if (get_option('utt_booking_tour_form', '') != '') {


    echo html_entity_decode(get_option('utt_booking_tour_form', ''));

    return false;
}
/*
 * Form Booking
 */

$postMetaBookingData = $uttour->getBookingData();
$tourMeta = get_post_meta(get_the_ID(), UTTTravelTour::$keyMeta, true);

$postMetaBookingData['time'] = $tourMeta['time'];

$beforePrice = UTTConfig::beforePrice();
$afterPrice = UTTConfig::afterPrice();
?>
<script>
    var UTTBookingTourData = {
        tourBooking: <?php echo json_encode($postMetaBookingData) ?>,
        totalChildren: '#totalChildren',
        totalAdults: '#totalAdults',
        totalMoney: '#totalMoney',
        numberAdults: '#number-adults',
        numberChildren: '#number-children',
        bookingDeparture: '#booking_departure',
        bookingHotels: '#booking_hotels',
        tourId: '<?php echo get_the_ID() ?>',
        tourTitle: '<?php echo get_the_title() ?>',
        tourImage: '<?php echo get_the_post_thumbnail_url() ?>'
    };
</script>
<form action="" onsubmit="tourSubmitBooking(event)" class='ut-form ut-product__form'>
    <input type="hidden" value="<?php echo get_the_ID() ?>" name="id">
    <div class="ut-product__section ut-row">
        <div class="ut-col-12 ut-col-sm-6">
            <div class="ut-form__group">
                <select name="booking_departure" id="booking_departure" class='ut-form__control'>
                    <?php
                        $schedules = $uttour->getSchedules();
                        foreach ($schedules as $schedule) :
                            echo '<option value="'. $schedule .'">'. $schedule .'</option>';
                        endforeach;
                    ?>
                </select>
            </div>
            <div class="ut-form__group">
                <div class='ut-row'>
                    <div class='ut-col-6 ut-product__qty'>
                        <input value="1" oninput="UTTBookingTour(event, UTTBookingTourData)" class='ut-form__control' id="number-adults" name="booking_adults" placeholder="Number Adults"/>
                    </div>
                    <div class='ut-col-6'>
                        <p class='ut-form__control ut-text--right'>
                            <strong><?php echo $beforePrice ?><?php echo UTTCurrencyFormat($uttour->getBookingData('price_adults_booking')) ?><?php echo $afterPrice ?></strong>
                        </p>
                    </div>
                </div>
            </div>
            <div class="ut-form__group">
                <p class='ut-form__control'>
                    <span><?php _e('Total Adults:', 'ultimate-travel') ?>  </span>
                    <strong class="ut-float--right">
                        <?php echo $beforePrice ?><span id="totalAdults"><?php echo UTTCurrencyFormat($uttour->getBookingData('price_adults_booking')) ?></span><?php echo $afterPrice ?>
                    </strong>
                </p>
            </div>
        </div>
        <div class='ut-col-12 ut-hidden--xl ut-hidden--lg ut-hidden--md ut-hidden--sm'>
            <hr class=''/>
        </div>
        <div class="ut-col-12 ut-col-sm-6">
            <div class="ut-form__group">
                <a class="" target="_blank" href="<?php echo $uttour->getHotel('link') ?>" title="<?php echo $uttour->getHotel('title') ?>">
                    <span class="fa fa-hotel"></span> <?php _e('Hotel', 'ultimate-travel') ?>:  <?php echo $uttour->getHotel('title') ?>
                </a>
            </div>
            <div class="ut-form__group">
                <div class='ut-row'>
                    <div class='ut-col-6 ut-product__qty'>
                        <input oninput="UTTBookingTour(event, UTTBookingTourData)" name="booking_children" id="number-children" class='ut-form__control' placeholder="Number Children"/>
                    </div>
                    <div class='ut-col-6'>
                        <p class='ut-form__control ut-text--right'>
                            <strong><?php echo $beforePrice ?><?php echo UTTCurrencyFormat($uttour->getBookingData('price_children_booking')) ?><?php echo $afterPrice ?></strong>
                        </p>
                    </div>
                </div>
            </div>
            <div class="ut-form__group">
                <p class='ut-form__control'>
                    <span><?php _e('Total Children:', 'ultimate-travel') ?> </span>
                    <strong class="ut-float--right">
                        <?php echo $beforePrice ?><span id="totalChildren">0</span><?php echo $afterPrice ?>
                    </strong>
                </p>
            </div>
        </div>
    </div>
    <hr />
    <div class="ut-product__section ut-row">
        <div class="ut-col ut-col-xl-6">
            <div class='ut-form__group'>
                <p class='ut-form__control'>
                    <strong><?php _e('Total Money:', 'ultimate-travel') ?> </strong>
                    <strong class="ut-float--right">
                        <?php echo $beforePrice ?><span id="totalMoney"><?php echo UTTCurrencyFormat($uttour->getBookingData('price_adults_booking')) ?></span><?php echo $afterPrice ?>
                    </strong>
                </p>
            </div>
        </div>
        <div class="ut-col ut-col-xl-6">
            <div class='ut-form__group'>
                <button
                        type="submit"
                        class="btn btn-primary btn-w170"
                        data-href="#tourBooking"
                >
                    <?php _e('BOOK NOW', 'ultimate-travel') ?></button>
            </div>
        </div>
    </div>
</form>

<?php
/*
 * END Form Booking
 */
?>

<!-- ut-popup-content mfp-with-anim mfp-hide -->
<a data-options='{"type":"inline","removalDelay":700}' data-effect="mfp-zoom-in" data-init="magnificPopup" class="opentourBooking" href="#tourBooking"></a>
<div class="ut-popup-content mfp-with-anim mfp-hide" id="tourBooking" >
    <h2><?php _e('Booking now', 'ultimate-travel') ?></h2>
    <p class="popup_desc"><?php _e('* Is this a required field.', 'ultimate-travel') ?></p>
    <div class="form-01">
        <div id="contact-form_frame"></div>
    </div>
</div>
