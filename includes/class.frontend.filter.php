<?php

if (get_option('uttinclude_asset', '') == 'on') {
    add_action('wp_enqueue_scripts', 'addStyle', 10000);
}


function addStyle()
{
    wp_register_style(
        'utt_css_main',
        plugin_dir_url(UTT_PATH) . '/asset/frontend/css/main.css'
    );
    wp_register_style('bootstrap_grid', plugin_dir_url(UTT_PATH) . '/asset/frontend/css/grid.css');
    wp_register_style('swiper', plugin_dir_url(UTT_PATH) . '/asset/frontend/css/swiper.css');
    wp_register_style('xdsoft_datetimepicker', plugin_dir_url(UTT_PATH) . '/asset/frontend/css/jquery.datetimepicker.min.css');
    wp_register_style('magnific-popup', plugin_dir_url(UTT_PATH) . '/asset/frontend/css/magnific-popup.css');

    wp_enqueue_style('bootstrap_grid');
    wp_enqueue_style('xdsoft_datetimepicker');
    wp_enqueue_style('utt_css_main');

    $key = get_option('cats-plugins-gooogleapikey');
    wp_register_script("cats-plugins-maps", "http://maps.google.com/maps/api/js?libraries=places&key={$key}&callback=catsInitMap", array("jquery", "utt_script_script-tour"));
    wp_register_script("cats-plugins-markerclusterer", "https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js", array("jquery", "utt_script_script-tour"));

    wp_enqueue_script(
            'utt_script_carousel',
        plugin_dir_url(UTT_PATH) . '/asset/frontend/js/owl.carousel.min.js',
        array('jquery')
    );

    wp_enqueue_script(
        'utt_script_affix',
        plugin_dir_url(UTT_PATH) . '/asset/frontend/js/affix.js',
        array('jquery')
    );

    wp_enqueue_script(
            'utt_script_gumshoe',
        plugin_dir_url(UTT_PATH) . '/asset/frontend/js/gumshoe.min.js',
        array('jquery')
    );

    wp_enqueue_script(
            'utt_script_modal',
        plugin_dir_url(UTT_PATH) . '/asset/frontend/js/modal.js',
        array('jquery')
    );

    wp_enqueue_script(
            'utt_script_wNumb',
        plugin_dir_url(UTT_PATH) . '/asset/frontend/js/wNumb.js',
        array('jquery')
    );

    wp_enqueue_script(
            'utt_script_nouislider.min',
        plugin_dir_url(UTT_PATH) . '/asset/frontend/js/nouislider.min.js',
        array('jquery')
    );

    $array = array(
        'js' =>   array(
            'utt_hotel_matchHeight' => plugin_dir_url(UTT_PATH) . "hotel/assets/vendors/jquery.matchHeight/jquery.matchHeight.min.js",
            'utt_hotel_magnific' => plugin_dir_url(UTT_PATH) . "hotel/assets/vendors/magnific-popup/jquery.magnific-popup.min.js",
            'utt_hotel_swiper' => plugin_dir_url(UTT_PATH) . "hotel/assets/vendors/swiper/swiper.jquery.js",
            'utt_hotel_datetimepicker' => plugin_dir_url(UTT_PATH) . "hotel/assets/vendors/datepicker/jquery.datetimepicker.full.js",
            'utt_hotel_main' => plugin_dir_url(UTT_PATH) . "hotel/assets/js/main.js",
        ),
    );

    foreach ($array['js'] as $key => $item) {
        wp_enqueue_script($key,$item, array('jquery'));
    }

    wp_enqueue_script('cats-plugins-maps');
    wp_enqueue_script('cats-plugins-markerclusterer');

    wp_enqueue_script(
            'utt_script_script-tour',
        plugin_dir_url(UTT_PATH) . '/asset/frontend/js/scripts.js',
        array(
            'jquery',
            'utt_script_carousel',
            'utt_script_affix',
            'utt_script_gumshoe',
            'utt_script_modal',
            'utt_script_wNumb',
            'utt_script_nouislider.min'
        )
    );

    wp_enqueue_script('thickbox', null,  array('jquery'), true);

    wp_enqueue_script(
        'utt_script_single-tour',
        plugin_dir_url(UTT_PATH) . '/asset/frontend/js/single-tour.js',
        array('jquery')
    );
}