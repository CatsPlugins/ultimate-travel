<?php


UTTTravelHotelAdmin::init();

class UTTTravelHotelAdmin
{
    public static $label = 'Hotel';

    public static function init()
    {
        add_filter('utt_tab_setting_header', 'UTTTravelHotelAdmin::addTabHeader');
        add_filter('utt_tab_setting_content', 'UTTTravelHotelAdmin::addBodyHeader');

        add_action('utt_save_option', 'UTTTravelHotelAdmin::saveOption');
    }

    public static function addTabHeader($args){
        $args[] = self::$label;

        return $args;
    }
    public static function addBodyHeader($args){
        /*
         * utt_rating_hotel
            utt_hotel_rating_criteria
         */

        if (@$_GET['tab'] == sanitize_title(self::$label)) {
            $contentTab = array(

                'hotel_rating_title' => array(
                    'type' => 'title',
                    'title' => __('Hotel Rating setting', 'ultimate-travel'),
                    'default' => ''
                ),
                'utt_rating_hotel' => array(
                    'type' => 'checkbox',
                    'title' => __('Enable rating', 'ultimate-travel'),
                    'default' => 'on'
                ),
                'utt_hotel_rating_criteria' => array(
                    'type' => 'textarea',
                    'title' => __('Criteria rating', 'ultimate-travel'),
                    'default' => '',
                    'attributes' => array(
                        'style' => 'width: 100%; height: 120px;'
                    ),
                    'desc' => '1 criteria per line'
                ),
                'hotel_title' => array(
                    'type' => 'title',
                    'title' => __('Hotel booking setting', 'ultimate-travel'),
                    'default' => ''
                ),
                'utt_booking_hotel' => array(
                    'type' => 'checkbox',
                    'title' => 'Enable booking <b>Hotel</b>',
                    'default' => 'on'
                ),
                'utt_booking_hotel_form' => array(
                    'type' => 'textarea',
                    'title' => 'Form booking code (allow shortcode)',
                    'attributes' => array(
                        'style' => 'width:100%; max-width: 600px;display: block; margin-bottom: 15px',
                        'rows' => 5
                    ),
                    'default' => ''
                ),
                'tour_title_email' => array(
                    'type' => 'title',
                    'title' => __('Email Template', 'ultimate-travel'),
                    'default' => ''
                ),
                'utt_booking_hotel_header' => array(
                    'type' => 'editor',
                    'title' => __('Header email', 'ultimate-travel'),
                    'default' => ''
                ),
                'utt_booking_hotel_footer' => array(
                    'type' => 'editor',
                    'title' => __('Footer email', 'ultimate-travel'),
                    'default' => ''
                ),
            );

            $args = $contentTab;
        }

        return $args;
    }
    public static function saveOption(){
        $post = isset($_POST['options']) ? $_POST['options'] : array();

        if (UTTTravelRequest::getQuery('tab', '') == sanitize_title(self::$label)) {
            $options = array(
                'utt_booking_hotel' => 'on',
                'utt_booking_hotel_form'  => 'html',
                'utt_booking_hotel_header' => 'html',
                'utt_booking_hotel_footer' => 'html',
                'utt_rating_hotel' => 'text',
                'utt_hotel_rating_criteria' => 'textarea'
            );

            foreach($options as $key => $item){
                if (isset($post[$key])) {
                    if ($item == 'html') {
                        $post[$key] = wp_kses_post($post[$key]);
                    } else if ($item == 'int') {
                        $post[$key] = (int)$post[$key];
                    } else if ($item == 'textarea'){
                        $post[$key] = sanitize_textarea_field($post[$key]);
                    } else {
                        $post[$key] = sanitize_text_field($post[$key]);
                    }

                    update_option($key,  $post[$key]);

                } else{
                    update_option($key, '');
                }
            }
        }
    }
}