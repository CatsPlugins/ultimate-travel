<?php


class UTTTourBooking {

    public static function init()
    {
        add_action('booking_tour_new', 'UTTTourBooking::sendEmail', 10, 2);
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

        ob_start();
        require UTTIncludeTemplatePart('email/booking-new', 'tour', '/tour/templates/');
        $html = ob_get_clean();

        $subject = __('Booking Tour from ' . get_bloginfo('name'));
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
        if (isset($_POST['databooking'])) {
            if (
                ! isset( $_POST['utt_tour_booking'] )
                || ! wp_verify_nonce( $_POST['utt_tour_booking'], 'form_booking' )
            ) {

            } else {
                $data = $_POST['databooking'];
                $tourName = get_the_title($data['id']);
                $authorTour = get_post_field('post_author', $data['id']);
                $data['tourid'] = $data['id'];
                $data['cats_product_id'] = $data['id'];
                $data['tourname'] = $tourName;
                $data['cats_product_auth'] = $authorTour;

                foreach ($data as $key => $value) {
                    $data[$key] = sanitize_text_field($value);
                    if($key == 'email') {
                        $data[$key] = sanitize_email($value);
                    }
                }

                $uttour = new uttour($data['id']);

                $adults = $data['adults'];
                $children = $data['children'];

                $totalAdult = (int)$adults * $uttour->getPriceAdults('price');
                $totalChild = (int)$children * $uttour->getPriceChildren('price');
                $totalAll = $totalAdult + $totalChild;

                $data['totalChildren'] = UTTCurrencyFormat($totalChild);
                $data['totalAdults'] = UTTCurrencyFormat($totalAdult);
                $data['totalMoney'] = UTTCurrencyFormat($totalAll);

                $data['tourBookingTime'] = $uttour->getMeta('time');

                $beforePrice = UTTConfig::beforePrice();
                $afterPrice = UTTConfig::afterPrice();

                $html = "
                <table>
                    <tr><td>Name: </td><td>{$data['name']}</td></tr>
                    <tr><td>Email: </td><td>{$data['email']}</td></tr>
                    <tr><td>Phone: </td><td>{$data['phone']}</td></tr>
                    <tr><td>Note: </td><td>{$data['note']}</td></tr>
                    
                    <tr><td colspan='2'><hr></td></tr>
                    <tr><td>Tour Name</td><td>{$data['tourname']}</td></tr>
                    <tr><td>Hotel</td><td>{$data['hotel']}</td></tr>
                    <tr><td>Time</td><td>{$data['tourBookingTime']}</td></tr>
                    <tr><td>Departure</td><td>{$data['departure']}</td></tr>
                    
                    <tr><td colspan='2'><hr></td></tr>
                    
                    <tr><td>Total Adults</td><td>{$beforePrice}{$data['totalAdults']}{$afterPrice}</td></tr>
                    <tr><td>Total Children</td><td>{$beforePrice}{$data['totalChildren']}{$afterPrice}</td></tr>
                    <tr><td>Total Money</td><td>{$beforePrice}{$data['totalMoney']}{$afterPrice}</td></tr>
                </table>
                ";

                $defaults = array(
                    'post_author' => 1,
                    'post_content' => $html,
                    'post_status' => 'pending',
                    'post_title' => '[TOUR] ' . implode(' ', array(
                        $data['name'],
                        'booking',
                        $tourName,
                        $data['departure']
                    )),
                    'meta_input' => $data,
                    'post_type' => UTTBooking::bookingType
                );
                $tourOrderID = wp_insert_post($defaults);
                if ($tourOrderID) {

                    update_post_meta($tourOrderID, UTTBooking::$keyBookingMeta, UTTBooking::getDefaultStatus());

                    UTTFlasSession::success('Booking Success');
                    do_action('booking_tour_new', $data, $tourOrderID);
                } else {
                    UTTFlasSession::error('Booking Error');
                }

                UTTBooking::afterBooking($uttour->tour->ID);

                die;
            }
        }
    }
}