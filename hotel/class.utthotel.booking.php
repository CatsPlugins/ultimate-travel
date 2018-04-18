<?php

//form_booking_hotel


add_action('booking_hotel_new', 'utthotelBooking::sendEmail', 10, 2);

add_action('init', function(){
    if (
        isset($_POST['utt_hotel_booking'])
        && wp_verify_nonce($_POST['utt_hotel_booking'], 'form_booking_hotel')
    ) {
        utthotelBooking::bookinghotel();
    }
});

class utthotelBooking
{
    public static function bookinghotel()
    {
        $post = $_POST;

        $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
        $room_name = isset($_POST['room_name']) ? sanitize_text_field($_POST['room_name']) : '';
        $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
        $phone = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
        $start_date = isset($_POST['start_date']) ? sanitize_text_field($_POST['start_date']) : '';
        $end_date = isset($_POST['end_date']) ? sanitize_text_field($_POST['end_date']) : '';
        $num_kid = isset($_POST['number_kid']) ? (int)$_POST['number_kid'] : '';
        $number_adults = isset($_POST['number_adults']) ? (int)$_POST['number_adults'] : '';
        $note = isset($_POST['note']) ? $_POST['note'] : '';
        $id = isset($_POST['id']) ? (int)$_POST['id'] : '';

        $redirectUrl = isset($_POST['redirectUrl']) ? (int)$_POST['redirectUrl'] : '';
        if (empty($redirectUrl)) {
            $redirectUrl = get_permalink($id);
        }

        $hotelName = get_the_title($id);

        $dataBooking = array(
            'name' => $name,
            'room_name' => $room_name,
            'email' => $email,
            'phone' => $phone,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'num_kid' => $num_kid,
            'number_adults' => $number_adults,
            'note' => $note,
            'hotelName' => $hotelName,
        );

        $authorHotel = get_post_field('post_author', $id);
        $dataBooking['hoteld'] = $id;
        $dataBooking['cats_product_id'] = $id;
        $dataBooking['cats_product_auth'] = $authorHotel;

        $html = "
                <table>
                    <tr><td>Name: </td><td>{$name}</td></tr>
                    <tr><td>Email: </td><td>{$email}</td></tr>
                    <tr><td>Phone: </td><td>{$phone}</td></tr>
                    <tr><td>Note: </td><td>{$note}</td></tr>
                    
                    <tr><td colspan='2'><hr></td></tr>
                    <tr><td>Hotel</td><td>{$hotelName}</td></tr>
                    <tr><td>Rom Name</td><td>{$room_name}</td></tr>
                    <tr><td>Time</td><td>{$start_date} - {$end_date}</td></tr>
                    
                    <tr><td colspan='2'><hr></td></tr>
                    
                    <tr><td>Adults</td><td>{$number_adults}</td></tr>
                    <tr><td>Children</td><td>{$num_kid}</td></tr>
                </table>
                ";

        $defaults = array(
            'post_author' => 1,
            'post_content' => $html,
            'post_status' => 'pending',
            'post_title' => '[HOTEL] ' . implode(' ', array(
                $hotelName,
                $room_name,
                $name,
                $start_date . ' - ' . $end_date
            )),
            'meta_input' => $dataBooking,
            'post_type' => UTTBooking::bookingType
        );

        $orderID = wp_insert_post($defaults);
        if ($orderID) {
            UTTFlasSession::success('Booking Hotel Success');
            update_post_meta($orderID, UTTBooking::$keyBookingMeta, UTTBooking::getDefaultStatus());
            do_action('booking_hotel_new', $dataBooking, $orderID);
        } else {
            UTTFlasSession::error('Booking Hotel Error');
        }

        UTTBooking::afterBooking($id);
        die;
    }



    public static function htmlEmail() {
        return 'text/html';
    }

    public static function sendEmail($data, $orderID)
    {
        add_filter( 'wp_mail_content_type', 'utthotelBooking::htmlEmail' );

        $emailAdmin = get_option('admin_email');
        $emailCustomer = (isset($data['email']) ? $data['email'] : '');
        $id = (isset($data['id']) ? $data['id'] : '');
        $author_id = get_post_field ('post_author', $id);
        $emailAuthor = get_the_author_meta( 'email' , $author_id );

        ob_start();
        require UTTIncludeTemplatePart('email/booking-new', 'hotel', '/hotel/templates/');
        $html = ob_get_clean();

        $subject = __('Booking Hotel from ' . get_bloginfo('name'));
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

        remove_filter( 'wp_mail_content_type', 'utthotelBooking::htmlEmail' );
    }

}