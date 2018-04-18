<?php
class UTTBooking {
    const bookingType = 'tour-booking';

    public $stautsBooking;

    public static $keyBookingMeta = 'cats_booking_status';

    public static function init()
    {
        self::registerPostType();

        add_action( 'wp_ajax_cats-change-status-booking', 'UTTBooking::changeStatusBookingAjax' );
    }

    public static function getDefaultStatus()
    {
        return apply_filters('ultimate-travel-booking-status-default', 'hold');
    }

    public static function getTextStatus($postID, $default = '')
    {
        $status = get_post_meta($postID, UTTBooking::$keyBookingMeta, true);
        if (isset( self::getAllStatus()[$status] )) {
            return self::getAllStatus()[$status];
        } else {
            return $default;
        }
    }


    public static function getAllStatus()
    {
        $status = array(
            'pending' => __('Pending', 'ultimate-travel'),
            'approve' => __('Approve', 'ultimate-travel'),
            'success' => __('Success', 'ultimate-travel'),
            'cancel' => __('Cancel', 'ultimate-travel')
        );
        return apply_filters('ultimate-travel-booking-status', $status);
    }

    private static function registerPostType()
    {
        register_post_type(UTTBooking::bookingType,
            [
                'labels'      => [
                    'name'          => __('Booking'),
                    'singular_name' => __('Booking'),
                ],
                'public'      => true,
                'publicly_queryable' => false,
                'exclude_from_search' => true,
                'has_archive' => false,
                'supports' => array('thumbnail', 'title', 'editor', 'custom-fields', 'comments'),
                'show_in_menu' => 'edit.php?post_type=' . UTTTravelTour::$postType
            ]
        );
    }

    public static function changeStatusBookingAjax()
    {

        if (wp_doing_ajax()) {
            $postId = UTTTravelRequest::getPost('booking_id', '');
            $newStatus = UTTTravelRequest::getPost('status', '');
            $oldStatus = get_post_meta($postId, UTTBooking::$keyBookingMeta, true);

            if (!isset(self::getAllStatus()[$newStatus])) {
                wp_send_json(array(
                    'status' => 400,
                    'message' => __('Status not allowed', 'ultimate-travel'),
                    'result' => $newStatus
                ));
            }

            if(
                get_current_user_id() == get_post_meta($postId, 'cats_product_auth', '') ||
                current_user_can('editor') ||
                current_user_can('administrator')
            ) {
                update_post_meta($postId, UTTBooking::$keyBookingMeta, $newStatus);

                wp_send_json(array(
                    'status' => 200,
                    'message' => __('Status updated', 'ultimate-travel'),
                    'result' => array(
                        'old' => $oldStatus,
                        'new' => $newStatus
                    )
                ));
            }
        }

        die;
    }

    public static function afterBooking($post_id)
    {
        $utt_after_booking = get_option('utt_after_booking', 'none');
        if ($utt_after_booking == 'none') {
            wp_redirect(get_permalink($post_id));
        } else {
            UTTFlasSession::destroy();
            wp_redirect(get_permalink($utt_after_booking));
        }
    }

}