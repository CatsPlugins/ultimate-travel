<?php

class UTTTravel
{
    public static function optionFields()
    {
        $pages = get_pages();
        $optionsPage = array(
            'none' => __('None', 'ultimate-travel')
        );
        foreach ($pages as $key => $item) {
            $optionsPage[$item->ID] = $item->post_title;
        }

        $options = array(
            'generalTab' => array(
                'type' => 'title',
                'title' => __('General Options', 'ultimate-travel')
            ),
            'uttcurrency' => array(
                'type' => 'text',
                'title' => __('Currency', 'ultimate-travel'),
                'default' => '$'
            ),
            'uttcurrency_position' => array(
                'type' => 'select',
                'title' => __('Currency position', 'ultimate-travel'),
                'default' => 'before',
                'options' => array(
                    'before' => __('Before price', 'ultimate-travel'),
                    'after' => __('After price', 'ultimate-travel'),
                )
            ),
            'uttdecimal' => array(
                'type' => 'text',
                'title' => __('Decimal symbol', 'ultimate-travel'),
                'default' => ','
            ),
            'uttthousand' => array(
                'type' => 'text',
                'title' => __('Thousand symbol', 'ultimate-travel'),
                'default' => '.'
            ),
            'cats-plugins-gooogleapikey' => array(
                'type' => 'text',
                'title' => __('Load Google Api Key (Frontend)', 'ultimate-travel'),
                'default' => 'AIzaSyAfp9YSnGuCPtmTMY'
            ),
            'uttinclude_asset' => array(
                'type' => 'checkbox',
                'title' => __('Load style default (Frontend)', 'ultimate-travel'),
                'desc' => __('Uncheck if override full template.', 'ultimate-travel'),
                'default' => 'on'
            ),
            'uttinclude_breadcrumbs' => array(
                'type' => 'checkbox',
                'title' => __('Display breadcrumbs', 'ultimate-travel'),
                'default' => 'on'
            ),
            'title_booking' => array(
                'type' => 'title',
                'title' => __('For booking', 'ultimate-travel')
            ),
            'utt_after_booking' => array(
                'type' => 'select',
                'title' => __('After booking success', 'ultimate-travel'),
                'default' => 'none',
                'options' => $optionsPage
            ),
        );

        $options = apply_filters('utt_admin_opions', $options);

        return $options;
    }
    public static function initPlugin()
    {
        if(is_admin()) {
            UTTFormElemt::js_wp_editor(array());
        }

        add_action('wp_head', 'UTTTravel::wp_header_action');

        add_image_size( UTTConfig::SIZE_THUMBNAIL, 400, 300, true);
        add_image_size( UTTConfig::SIZE_BIG, 800, 600, true);

        UTTBooking::init();
    }

    public static function wp_header_action()
    {
        $dataJsUTT = array(
            'thousandPoint' => UTTThousandPoint(),
            'decimalPoint' => UTTDecimalPoint(),
            'beforeCurrency' => UTTConfig::beforePrice(),
            'afterCurrency' => UTTConfig::afterPrice(),
            'ajaxurl' => admin_url('admin-ajax.php')
        );

        echo '<script>var dataJsUTT = '.json_encode($dataJsUTT).'</script>';
    }
}
