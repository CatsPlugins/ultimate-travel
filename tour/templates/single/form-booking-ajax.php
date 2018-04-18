<?php

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
if (!isset($_POST['id'])) {
    return false;
}
$id = $_POST['id'];
$uttour = new uttour($id);


$postMetaBookingData = $uttour->getBookingData();
$tourMeta = get_post_meta(get_the_ID(), UTTTravelTour::$keyMeta, true);

$postMetaBookingData['time'] = $tourMeta['time'];

$beforePrice = UTTConfig::beforePrice();
$afterPrice = UTTConfig::afterPrice();

$adults = UTTTravelRequest::getPost('booking_adults', 1);
$children = UTTTravelRequest::getPost('booking_children', 1);
$hotels = $uttour->getHotel('link');
$departure = UTTTravelRequest::getPost('booking_departure', '');

$totalAdult = (int)$adults * $uttour->getPriceAdults('price');
$totalChild = (int)$children * $uttour->getPriceChildren('price');
$totalAll = $totalAdult + $totalChild;
?>

    <form action="" method="post" class="text-left">
        <?php wp_nonce_field( 'form_booking', 'utt_tour_booking', false, true) ?>

        <input type="hidden" value="<?php echo $departure ?>" name="databooking[departure]" id="">
        <input type="hidden" value="<?php echo $hotels ?>" name="databooking[hotel]" id="">
        <input type="hidden" value="<?php echo $adults ?>" name="databooking[adults]" id="">
        <input type="hidden" value="<?php echo $children ?>" name="databooking[children]" id="">

        <input type="hidden" name="databooking[id]" id="" value="<?php echo UTTTravelRequest::getPost('id') ?>">

        <div class="ut-row ut-widget ut-nopadding--left ut-nopadding--right">
            <div class="ut-col-12 ut-col-sm-4">
                <span><?php _e('Departure day:', 'ultimate-travel') ?></span>
                <label class='ut-form__label' id="fbooking_departure"><?php echo $departure ?><?php ?></label>
            </div>
            <div class="ut-col-12 ut-col-sm-4">
                <span><?php _e('Hotel:', 'ultimate-travel') ?></span>
                <label class='ut-form__label' id="fbooking_hotel">
                    <a class="" target="_blank" href="<?php echo $uttour->getHotel('link') ?>" title="<?php echo $uttour->getHotel('title') ?>">
                       <?php echo $uttour->getHotel('title') ?>
                    </a>
                </label>
            </div>
            <div class="ut-col-12 ut-col-sm-4">
                <span><?php _e('Time:', 'ultimate-travel') ?></span>
                <label class='ut-form__label' id="fbooking_time"><?php echo $uttour->getMeta('time') ?></label>
            </div>
        </div>
        <hr/>
        <div class="ut-row ut-widget ut-nopadding--left ut-nopadding--right">
            <div class="ut-col-12 ut-col-sm-4">
                <span><?php _e('Total Adults:', 'ultimate-travel') ?></span>
                <label class='ut-form__label' id="fbooking_ad"><?php echo $beforePrice . UTTCurrencyFormat($totalAdult) . $afterPrice ?></label>
            </div>
            <div class="ut-col-12 ut-col-sm-4">
                <span><?php _e('Total Children:', 'ultimate-travel') ?></span>
                <label class='ut-form__label' id="fbooking_ch"><?php echo $beforePrice . UTTCurrencyFormat($totalChild) . $afterPrice ?></label>
            </div>
            <div class="ut-col-12 ut-col-sm-4">
                <span><?php _e('Total money:', 'ultimate-travel') ?></span>
                <label class='ut-form__label' id="fbooking_money"><?php echo $beforePrice . UTTCurrencyFormat($totalAll) . $afterPrice ?></label>
            </div>
        </div>
        <hr/>
        <div class='ut-row ut-widget ut-nopadding--left ut-nopadding--right'>
            <div class="ut-col-12 ut-col-sm-4">
                <div class='ut-form__group'>
                    <label class="ut-form__label"><?php _e('Gender *', 'ultimate-travel') ?></label>
                    <select required class='ut-form__control' name="databooking[gender]">
                        <option value='male'><?php _e('Male', 'ultimate-travel') ?></option>
                        <option value='female'><?php _e('Female', 'ultimate-travel') ?></option>
                    </select>
                </div>
            </div>
            <div class="ut-col-sm-8 ut-col-12">
                <div class='ut-form__group'>
                    <label class="ut-form__label"><?php _e('Full name *', 'ultimate-travel') ?></label>
                    <input required class='ut-form__control' name="databooking[name]"/>
                </div>
            </div>
        </div>

        <div class='ut-row'>
            <div class="ut-col-6" >
                <div class='ut-form__group'>
                    <label class="ut-form__label"><?php _e('Email *', 'ultimate-travel') ?></label>
                    <input class='ut-form__control' name="databooking[email]"/>
                </div>
            </div>
            <div class="ut-col-6" >
                <div class='ut-form__group'>
                    <label class="ut-form__label"><?php _e('Phone *', 'ultimate-travel') ?></label>
                    <input required class='ut-form__control' name="databooking[phone]"/>
                </div>
            </div>
            <div class="ut-col-12">
                <div class='ut-form__group'>
                    <label class="ut-form__label"><?php _e('Note', 'ultimate-travel') ?> </label>
                    <textarea class='ut-form__control' name="databooking[note]" cols='30' rows='3'></textarea>
                </div>
                <div class='ut-form__group'>
                    <button class='btn btn-primary' type='submit'><?php _e('BOOK NOW', 'ultimate-travel') ?></button>
                </div>
            </div>
        </div>
    </form>
<?php
/*
 * END Form Booking
 */
?>
