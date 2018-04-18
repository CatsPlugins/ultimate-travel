<?php

$orderDetail = get_post($orderID);
$cssTd = 'padding: 10px;';

$hotelName = $data['hotelName'] . ' - ' . $data['room_name'];
?>

<div style="max-width: 700px; margin: auto">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <?php echo apply_filters('the_content', html_entity_decode(get_option('utt_booking_hotel_header'))) ?>

    <table style=" width: 100%; border-collapse: collapse; margin: 0 auto;font-size: 15px;  border:1px solid #2780e3; " cellspacing="0" cellpadding="0" border="1">
        <thead>
        <tr>
            <th colspan="3" align="" style="background: rgb(0,112,192); color: #fff; height: 35px; padding: 0 10px; text-align: left;">
                <?php _e('Booking Hotel data ', 'ultimate-travel') ?>
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
                <td rowspan="5" style="padding: 0 10px; font-size: 30px; color: #ff0000; font-weight: bold; text-align: center"><?php echo $orderDetail->ID ?></td>
                <td style="padding: 0 10px"><strong><?php _e('Hotel', 'ultimate-travel') ?></strong></td>
                <td style="padding: 0 10px">
                    <?php echo $hotelName ?>
                </td>
            </tr>

            <tr style="height: 30px;">
                <td style="padding: 0 10px"><strong><?php _e('Start date', 'ultimate-travel') ?></strong></td>
                <td style="padding: 0 10px"><?php echo sanitize_text_field(@$data['start_date']) ?></td>
            </tr>

            <tr style="height: 30px;">
                <td style="padding: 0 10px"><strong><?php _e('End date', 'ultimate-travel') ?></strong></td>
                <td style="padding: 0 10px">
                    <?php echo sanitize_text_field(@$data['end_date']) ?>
                </td>
            </tr>

            <tr style="height: 30px;">
                <td style="padding: 0 10px"><strong><?php _e('Number kid', 'ultimate-travel') ?></strong></td>
                <td style="padding: 0 10px">
                    <?php echo sanitize_text_field(@$data['number_kid']) ?>
                </td>
            </tr>

            <tr style="height: 30px;">
                <td style="padding: 0 10px"><strong><?php _e('Number Adults', 'ultimate-travel') ?></strong></td>
                <td style="padding: 0 10px">
                    <?php echo sanitize_text_field(@$data['number_adults']) ?>
                </td>
            </tr>

            
        </tbody>
    </table>



    <table style="width: 100%; background-color: rgb(211,223,238);border-collapse: collapse; border:1px solid #2780e3;font-size: 15px; margin: 10px auto" border="1" cellspacing="0" cellpadding="0">
        <thead>
        <tr>
            <th colspan="4" style="background: rgb(0,112,192); color: #fff; height: 35px; padding: 0 10px; text-align: left;">
                <?php _e('2. BOOKING INFORMATION', 'ultimate-travel') ?>
            </th>
        </tr>
        </thead>
        <tbody>
            <tr>
                <td style="padding: 5px 10px"><?php _e('Full name', 'ultimate-travel') ?></td>
                <td style="padding:5px 10px"><?php echo $data['name'] ?></td>
            </tr>
            <tr>
                <td style="padding: 5px 10px"><?php _e('Email', 'ultimate-travel') ?></td>
                <td style="padding:5px 10px"><?php echo $data['email'] ?></td>
            </tr>
            <tr>
                <td style="padding: 5px 10px"><?php _e('Phone', 'ultimate-travel') ?></td>
                <td style="padding:5px 10px"><?php echo $data['phone'] ?></td>
            </tr>
            <tr>
                <td style="padding: 5px 10px"><?php _e('Note', 'ultimate-travel') ?></td>
                <td style="padding:5px 10px"><?php echo $data['note'] ?></td>
            </tr>
        </tbody>
    </table>

    <?php $contentAfter = apply_filters('the_content', html_entity_decode(get_option('utt_booking_hotel_footer'))) ?>
    <?php if (!empty($contentAfter)) : ?>
        <div style="box-sizing: border-box; border-radius: 10px; border: 3pt solid rgb(0,176,240); margin-top: 20px; margin-bottom: 15px; padding: 10px; text-align: justify;">
            <?php echo $contentAfter; ?>
        </div>
    <?php endif; ?>

</div>