<?php
$carOrder = get_post($orderTourId);
$car = get_post($id);

$cssTd = 'padding: 10px;';
?>

<div style="max-width: 700px; margin: auto">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <?php echo apply_filters('the_content', html_entity_decode(get_option('utt_booking_car_header'))) ?>

    <table style=" width: 100%; border-collapse: collapse; margin: 0 auto;font-size: 15px;  border:1px solid #2780e3; " cellspacing="0" cellpadding="0" border="1">
        <thead>
        <tr>
            <th colspan="3" align="" style="background: rgb(0,112,192); color: #fff; height: 35px; padding: 0 10px; text-align: left;">
                <?php _e('Booking Data', 'ultimate-travel') ?>
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
            <td rowspan="4" style="padding: 0 10px; font-size: 30px; color: #ff0000; font-weight: bold; text-align: center"><?php echo $carOrder->ID ?></td>
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
            <td style=""><strong><?php _e('Name', 'ultimate-travel') ?></strong></td>
            <td style=""><strong><?php _e('Get car', 'ultimate-travel') ?></strong></td>
            <td style=""><strong><?php _e('Return car', 'ultimate-travel') ?></strong></td>
            <td style=""><strong><?php _e('Point of departure', 'ultimate-travel') ?></strong></td>
            <td style=""><strong><?php _e('Destination', 'ultimate-travel') ?></strong></td>
            <td style=""><strong><?php _e('Options', 'ultimate-travel') ?></strong></td>
        </tr>
        <tr style="height: 30px">
            <td><?php echo $car->post_title ?></td>
            <td><?php echo sanitize_text_field(@$data['form_date']) ?></td>
            <td><?php echo sanitize_text_field(@$data['to_date']) ?></td>
            <td><?php echo sanitize_text_field(@$data['point_start']) ?></td>
            <td><?php echo sanitize_text_field(@$data['destination']) ?></td>
            <td>
                <?php echo isset($data['one-way']) && $data['one-way'] == 'yes' ? 'One way, ' : '' ?>
                <?php echo isset($data['round-trip']) && $data['round-trip'] == 'yes' ? 'Round Trip' : '' ?>
            </td>
        </tr>
        </tbody>
    </table>

    <div style="box-sizing: border-box; border-radius: 10px; border: 3pt solid rgb(0,176,240); margin-top: 20px; margin-bottom: 15px; padding: 10px; text-align: justify;">
        <?php echo apply_filters('the_content', html_entity_decode(get_option('utt_booking_car_footer'))) ?>
    </div>

</div>
