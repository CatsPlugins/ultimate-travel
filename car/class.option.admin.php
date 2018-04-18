<?php


UTTTravelCarAdmin::init();

class UTTTravelCarAdmin
{
    public static $label = 'Car';

    public static function init()
    {
        add_filter('utt_tab_setting_header', 'UTTTravelCarAdmin::addTabHeader');
        add_filter('utt_tab_setting_content', 'UTTTravelCarAdmin::addBodyHeader');

        add_action('utt_save_option', 'UTTTravelCarAdmin::saveOption');
    }

    public static function addTabHeader($args){
        $args[] = self::$label;

        return $args;
    }
    public static function addBodyHeader($args){
        if (@$_GET['tab'] == sanitize_title(self::$label)) {
            $contentTab = array(
                'car_rating_title' => array(
                    'type' => 'title',
                    'title' => __('Car Rating setting', 'ultimate-travel'),
                    'default' => ''
                ),
                'utt_rating_car' => array(
                    'type' => 'checkbox',
                    'title' => __('Enable rating', 'ultimate-travel'),
                    'default' => 'on'
                ),
                'utt_car_rating_criteria' => array(
                    'type' => 'textarea',
                    'title' => __('Criteria rating', 'ultimate-travel'),
                    'default' => '',
                    'attributes' => array(
                        'style' => 'width: 100%; height: 120px;'
                    ),
                    'desc' => '1 criteria per line'
                ),
                'car_title' => array(
                    'type' => 'title',
                    'title' => __('Car booking setting', 'ultimate-travel'),
                    'default' => ''
                ),

                'utt_booking_car' => array(
                    'type' => 'checkbox',
                    'title' => 'Enable booking <b>Car</b>',
                    'default' => 'on'
                ),
                'utt_booking_car_form' => array(
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
                'utt_booking_car_header' => array(
                    'type' => 'editor',
                    'title' => __('Header email', 'ultimate-travel'),
                    'default' => ''
                ),
                'utt_booking_car_footer' => array(
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
                'utt_booking_car' => 'on',
                'utt_booking_car_form'  => 'html',
                'utt_booking_car_header' => 'html',
                'utt_booking_car_footer' => 'html',
                'utt_rating_car' => 'text',
                'utt_car_rating_criteria' => 'textarea'
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