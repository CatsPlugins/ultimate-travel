<?php

UTTTravelTourAdmin::init();

class UTTTravelTourAdmin
{
    public static $label = 'Tour';

    public static function init()
    {
        add_filter('utt_tab_setting_header', 'UTTTravelTourAdmin::addTabHeader');
        add_filter('utt_tab_setting_content', 'UTTTravelTourAdmin::addBodyHeader');

        add_action('utt_save_option', 'UTTTravelTourAdmin::saveOption');
    }

    public static function addTabHeader($args){
        $args[] = self::$label;

        return $args;
    }
    public static function addBodyHeader($args){
        if (@$_GET['tab'] == sanitize_title(self::$label)) {

            $dayLastMinutes = array(
                'notset' => __('Not set', 'ultimate-travel')
            );
            for($i = 1; $i <= 45; $i ++) {
                $dayLastMinutes[$i] = $i . ' day';
            }

            $contentTab = array(
                'tour_general_title' => array(
                    'type' => 'title',
                    'title' => __('Tour setting general', 'ultimate-travel'),
                    'default' => ''
                ),
                'utt_related_tour' => array(
                    'type' => 'checkbox',
                    'title' => __('Show related tour <b>tour</b>', 'ultimate-travel'),
                    'default' => 'on'
                ),
                'tour_rating_title' => array(
                    'type' => 'title',
                    'title' => __('Tour Rating setting', 'ultimate-travel'),
                    'default' => ''
                ),
                'utt_rating_tour' => array(
                    'type' => 'checkbox',
                    'title' => __('Enable rating <b>tour</b>', 'ultimate-travel'),
                    'default' => 'on'
                ),
                'utt_rating_criteria' => array(
                    'type' => 'textarea',
                    'title' => __('Criteria rating <b>tour</b>', 'ultimate-travel'),
                    'default' => '',
                    'attributes' => array(
                        'style' => 'width: 100%; height: 120px;'
                    ),
                    'desc' => '1 criteria per line'
                ),
                'tour_booking_title' => array(
                    'type' => 'title',
                    'title' => __('Booking setting', 'ultimate-travel'),
                    'default' => ''
                ),
                'tour_last_minutes' => array(
                    'type' => 'select',
                    'title' => __('Number day last minutes tour', 'ultimate-travel'),
                    'default' => '5',
                    'options' => $dayLastMinutes
                ),
                'utt_booking_tour' => array(
                    'type' => 'checkbox',
                    'title' => __('Enable booking <b>tour</b>', 'ultimate-travel'),
                    'default' => 'on'
                ),
                'utt_booking_tour_form' => array(
                    'type' => 'textarea',
                    'title' => __('Form booking code (allow shortcode)', 'ultimate-travel'),
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
                'utt_booking_tour_header' => array(
                    'type' => 'editor',
                    'title' => __('Header email', 'ultimate-travel'),
                    'default' => ''
                ),
                'utt_booking_tour_footer' => array(
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
                'utt_booking_tour' => 'text',
                'utt_related_tour' => 'text',
                'utt_booking_tour_form'  => 'html',
                'utt_booking_tour_header' => 'html',
                'utt_booking_tour_footer' => 'html',
                'tour_last_minutes' => 'int',
                'utt_rating_tour' => 'text',
                'utt_rating_criteria' => 'textarea'
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