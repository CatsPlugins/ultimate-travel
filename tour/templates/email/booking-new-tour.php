<?php
$tourOrder = get_post($orderTourId);
$tour = get_post($id);

$idDepartureTour = get_post_meta($tour->ID, '_departure', true);
$departure = '';
if ($idDepartureTour > 0) {
    $term = get_term($idDepartureTour);
    if ($term) {
        $departure = $term->name;
    }
}

$journeyTerm = wp_get_post_terms($tour->ID, 'region');
$journey = array();
foreach ($journeyTerm as $item) {
    $journey[] = $item->name;
}
$journey = implode(', ', $journey);

$postMetaBookingData = get_post_meta($tour->ID, UTTTravelTour::metaKeyBooking, true);

$cssTd = 'padding: 10px;';
?>

<div style="max-width: 700px; margin: auto">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <?php echo apply_filters('the_content', html_entity_decode(get_option('utt_booking_tour_header'))) ?>

    <table style=" width: 100%; border-collapse: collapse; margin: 0 auto;font-size: 15px;  border:1px solid #2780e3; " cellspacing="0" cellpadding="0" border="1">
        <thead>
            <tr>
                <th colspan="3" align="" style="background: rgb(0,112,192); color: #fff; height: 35px; padding: 0 10px; text-align: left;">
                    <?php _e('Booking data ', 'ultimate-travel') ?>
                </th>
            </tr>
        </thead>
        <tbody>

            <tr style="height: 30px;">
                <td style="padding: 0 10px">
                    <strong><?php _e('Oder ID', 'ultimate-travel') ?></strong></td>
                <td style="padding: 0 10px">
                    <strong><?php _e('Status', 'ultimate-travel') ?></strong></td>
                <td style="padding: 0 10px"><?php _e('Holding', 'ultimate-travel') ?></td>
            </tr>

            <tr style="height: 30px;">
                <td rowspan="5" style="padding: 0 10px; font-size: 30px; color: #ff0000; font-weight: bold; text-align: center"><?php echo $tourOrder->ID ?></td>
                <td style="padding: 0 10px"><strong><?php _e('Departure date', 'ultimate-travel') ?></strong></td>
                <td style="padding: 0 10px"><?php echo sanitize_text_field(@$data['departure']) ?></td>
            </tr>
            <tr style="height: 30px;">
                <td style="padding: 0 10px"><strong><?php _e('Departure', 'ultimate-travel') ?></strong></td>
                <td style="padding: 0 10px">
                    <?php echo $departure ?>
                </td>
            </tr>
            <tr style="height: 30px;">
                <td style="padding: 0 10px"><strong><?php _e('Name', 'ultimate-travel') ?></strong></td>
                <td style="padding: 0 10px"><?php echo sanitize_text_field(@$data['name']) ?></td>
            </tr>
            <tr style="height: 30px;">
                <td style="padding: 0 10px">
                    <strong><?php _e('Phone', 'ultimate-travel') ?></strong>
                </td>
                <td style="padding: 0 10px"><?php echo sanitize_text_field(@$data['phone']) ?></td>
            </tr>
            <tr style="height: 30px;">
                <td style="padding: 0 10px"><strong><?php _e('Email', 'ultimate-travel') ?></strong></td>
                <td style="padding: 0 10px"><?php echo sanitize_email(@$data['email']) ?></td>
            </tr>
            <tr style="border: 1px solid transparent; border-top-color: rgb(0, 112, 192)">
                <td colspan="3">&nbsp;</td>
            </tr>
        </tbody>
    </table>


    <table style="width: 100%; background-color: rgb(211,223,238);border-collapse: collapse; text-align: center;  border:1px solid #2780e3;font-size: 15px; margin: 10px auto" border="1" cellspacing="0" cellpadding="0">
        <thead>
        <tr>
            <th colspan="4" style="background: rgb(0,112,192); color: #fff; height: 35px; padding: 0 10px; text-align: left;">
                <?php _e('2. BOOKING INFORMATION', 'ultimate-travel') ?>
            </th>
        </tr>
        </thead>
        <tbody>
        <tr style="height: 30px">
            <td style=""><strong><?php _e('ID Tour', 'ultimate-travel') ?></strong></td>
            <td style=""><strong><?php _e('Name', 'ultimate-travel') ?></strong></td>
            <td style=""><strong><?php _e('Departure', 'ultimate-travel') ?></strong></td>
            <td style=""><strong><?php _e('Journey', 'ultimate-travel') ?></strong></td>
            </tr><tr style="height: 30px">
            <td style="<?php echo $cssTd ?>"><strong><?php echo $tour->ID ?></strong></td>
            <td style="<?php echo $cssTd ?>">
                <?php echo $tour->post_title ?>
            </td>
            <td style="<?php echo $cssTd ?>">
                <?php echo $departure ?>
            </td>
            <td style="<?php echo $cssTd ?>">
                <?php echo $journey ?>
            </td>
        </tr>
        </tbody>
    </table>

    <table style="width: 100%; background-color: rgb(211,223,238);border-collapse: collapse; text-align: center;  border:1px solid #2780e3;font-size: 15px; margin: 10px auto" border="1" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <th colspan="4" style="background: rgb(0,112,192); color: #fff; height: 35px; padding: 0 10px; text-align: left;">
                    <?php _e('3. PAYMENT INFORMATION', 'ultimate-travel') ?>
                </th>
            </tr>
        </thead>

        <tbody>
            <tr style="height: 30px">
                <td><strong><?php _e('Quantity', 'ultimate-travel') ?></strong></td>
                <td><strong><?php _e('Customer', 'ultimate-travel') ?></strong></td>
                <td><strong><?php _e('Price', 'ultimate-travel') ?>r</strong></td>
                <td><strong><?php _e('Total', 'ultimate-travel') ?></strong></td>
            </tr>
            <tr style="height: 30px">
                <td><strong><?php echo  @$data['adults'] ?></strong></td>
                <td><?php _e('Audult', 'ultimate-travel') ?></td>
                <td><?php echo UTTCurrencyFormat($postMetaBookingData['price_adults']) ?></td>
                <td><?php echo @$data['totalAdults'] ?></td>
            </tr>
            <tr style="height: 30px">
                <td><strong><?php echo  @$data['children'] ?></strong></td>
                <td><?php _e('Children', 'ultimate-travel') ?></td>
                <td><?php echo UTTCurrencyFormat($postMetaBookingData['price_children']) ?></td>
                <td><?php echo @$data['totalChildren'] ?></td>
            </tr>
            <tr style="height: 30px">
                <td colspan="3"><strong><?php _e('Total', 'ultimate-travel') ?></strong></td>
                <td><strong style="color: #ff0000"><?php echo @$data['totalMoney'] ?></strong></td>
            </tr>
        </tbody>

    </table>


    <div style="box-sizing: border-box; border-radius: 10px; border: 3pt solid rgb(0,176,240); margin-top: 20px; margin-bottom: 15px; padding: 10px; text-align: justify;">
        <?php echo apply_filters('the_content', html_entity_decode(get_option('utt_booking_tour_footer'))) ?>
    </div>

</div>