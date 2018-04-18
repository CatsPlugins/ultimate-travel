<?php


class UTTCarBooking {

    public static function init()
    {
        add_action('booking_car_new', 'UTTCarBooking::sendEmail', 10, 2);
        self::saveBooking();
    }

    public static function htmlEmail() {
        return 'text/html';
    }

    public static function sendEmail($data, $orderTourId)
    {
        add_filter( 'wp_mail_content_type', 'UTTTourBooking::htmlEmail' );

        $emailAdmin = get_option('admin_email');
        $emailCustomer = (isset($data['email']) ? $data['email'] : '');
        $id = (isset($data['id']) ? $data['id'] : '');
        $author_id = get_post_field ('post_author', $id);
        $emailAuthor = get_the_author_meta( 'email' , $author_id );
        $carName = get_the_title($data['id']);

        ob_start();
        require UTTIncludeTemplatePart('email/booking-new', 'car', '/car/templates/');
        $html = ob_get_clean();

        foreach ($data as $key => $value) {
            $data[$key] = sanitize_text_field($value);
            if($key == 'email') {
                $data[$key] = sanitize_email($value);
            }
        }

        $subject = __('Booking Car from ' . get_bloginfo('name'));
        $body = $html;
        $headers = array('Content-Type: text/html; charset=UTF-8');

        $multiple_recipients = array(
            $emailAdmin
        );

        if (filter_var($emailCustomer, FILTER_VALIDATE_EMAIL)) {
            $multiple_recipients[] = $emailCustomer;
        }
        if (filter_var($emailAuthor, FILTER_VALIDATE_EMAIL)) {
            $multiple_recipients[] = $emailAuthor;
        }

        wp_mail( $multiple_recipients, $subject, $body, $headers);

        remove_filter( 'wp_mail_content_type', 'UTTTourBooking::htmlEmail' );
    }

    private static function saveBooking()
    {
        if (isset($_POST['databookingcar'])) {
            if (
                ! isset( $_POST['utt_car_booking'] )
                || ! wp_verify_nonce( $_POST['utt_car_booking'], 'form_booking' )
            ) {

            } else {
                $data = $_POST['databookingcar'];
                $carName = get_the_title($data['id']);
                $data['carname'] = $carName;

                $authorCar = get_post_field('post_author', $data['id']);
                $data['cats_product_id'] = $data['id'];
                $data['cats_product_auth'] = $authorCar;

                $currency = UTTCurrency();

                foreach ($data as $key => $value) {
                    $data[$key] = sanitize_text_field($value);
                    if($key == 'email') {
                        $data[$key] = sanitize_email($value);
                    }
                }

                $html = "
                <table>
                    <tr><td>Name: </td><td>{$data['name']}</td></tr>
                    <tr><td>Email: </td><td>{$data['email']}</td></tr>
                    <tr><td>Phone: </td><td>{$data['phone']}</td></tr>
                    
                    <tr><td colspan='2'><hr></td></tr>
                    <tr><td>Car Name</td><td>{$carName}</td></tr>
                    <tr><td>Get car</td><td>{$data['form_date']}</td></tr>
                    <tr><td>Return car</td><td>{$data['to_date']}</td></tr>
                    <tr><td>Point of departure</td><td>{$data['point_start']}</td></tr>
                    <tr><td>Destination</td><td>{$data['destination']}</td></tr>
                    <tr><td>One-way</td><td>". @$data['one-way'] ."</td></tr>
                    <tr><td>Round-trip</td><td>". @$data['round-trip'] ."</td></tr>
                    
                </table>
                ";

                $defaults = array(
                    'post_author' => 1,
                    'post_content' => $html,
                    'post_status' => 'pending',
                    'post_title' => '[CAR] ' . implode(' ', array(
                            $data['name'],
                            'booking',
                            $carName,
                            @$data['departure']
                        )),
                    'meta_input' => $data,
                    'post_type' => UTTBooking::bookingType
                );
                $orderID = wp_insert_post($defaults);
                if ($orderID) {

                    update_post_meta($orderID, UTTBooking::$keyBookingMeta, UTTBooking::getDefaultStatus());

                    UTTFlasSession::success('Booking Success');
                    do_action('booking_car_new', $data, $orderID);
                } else {
                    UTTFlasSession::error('Booking Error');
                }

                UTTBooking::afterBooking($data['id']);
                die;
            }
        }
    }
}
