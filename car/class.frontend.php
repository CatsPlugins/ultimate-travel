<?php

class UTTCarFrontend
{
    public static function init(){
        add_action('wp_enqueue_scripts', __CLASS__ . '::addStyle', 10000);
        add_action( 'pre_get_posts', 'UTTCarFrontend::editQueryArchive' );
    }

    public static function addStyle()
    {
        if ( is_singular( UTTTravelCar::postType ) || is_tax( get_object_taxonomies( UTTTravelCar::postType ))) {

            wp_enqueue_style(
                'utt_css_tour_owl.carousel',
                plugin_dir_url(UTT_PATH) . '/asset/frontend/css/owl.carousel.min.css'
            );

            wp_enqueue_style(
                'utt_css_tour_owl.theme.default',
                plugin_dir_url(UTT_PATH) . '/asset/frontend/css/owl.theme.default.min.css'
            );
            wp_enqueue_style(
                'utt_css_tour_ut-global',
                plugin_dir_url(UTT_PATH) . '/asset/frontend/css/ut-global.css'
            );

            wp_enqueue_style(
                'utt_css_tour_styles',
                plugin_dir_url(UTT_PATH) . '/asset/frontend/css/styles.css'
            );

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
                'utt_script_moment',
                plugin_dir_url(UTT_PATH) . '/asset/frontend/js/moment.js',
                array('jquery')
            );

            wp_enqueue_script(
                'utt_script_pikaday',
                plugin_dir_url(UTT_PATH) . '/asset/frontend/js/pikaday.js',
                array('jquery')
            );

            if (is_singular()) {
                wp_enqueue_script(
                    'utt_script_car',
                    plugin_dir_url(UTT_PATH) . '/asset/frontend/js/single-car.js',
                    array('jquery')
                );
            } else {
                wp_enqueue_script(
                    'utt_script_car',
                    plugin_dir_url(UTT_PATH) . '/asset/frontend/js/scripts.js',
                    array('jquery')
                );
                wp_enqueue_script(
                    'utt_script_car',
                    plugin_dir_url(UTT_PATH) . '/asset/frontend/js/archive-car.js',
                    array('jquery')
                );
            }

        }
    }

    public static function editQueryArchive(WP_Query $query)
    {
        if (is_archive() &&  $query->is_main_query() && is_tax( get_object_taxonomies( UTTTravelCar::postType ) )) {
            $order = UTTTravelRequest::getQuery('order', 1);
            switch ($order) {
                case 1:
                    $query->set('order', 'ASC');
                    $query->set('orderby', 'modified');
                    break;
                case 2:
                    $query->set('order', 'DESC');
                    $query->set('orderby', 'modified');
                    break;
                case 3:
                    $query->set('order', 'DESC');
                    $query->set('orderby', 'title');
                    break;
                case 4:
                    $query->set('order', 'ASC');
                    $query->set('orderby', 'title');
                    break;
                default:
                    $query->set('order', 'DESC');
                    $query->set('orderby', 'modified');
                    break;
            }

            $filter  = UTTTravelRequest::getQuery('f', array());

            $taxQuery = array();
            $cfQuery = array();

            if (isset($filter[UTTTravelCar::seats]) && is_array($filter[UTTTravelCar::seats])) {
                $taxQuery[] = array(
                    'taxonomy' => UTTTravelCar::seats,
                    'terms' => $filter[UTTTravelCar::seats],
                    'operator' => 'IN'
                );
            }

            if (isset($filter[UTTTravelCar::trademark]) && is_array($filter[UTTTravelCar::trademark])) {
                $taxQuery[] = array(
                    'taxonomy' => UTTTravelCar::trademark,
                    'terms' => $filter[UTTTravelCar::trademark],
                    'operator' => 'IN'
                );
            }

            if (isset($filter[UTTTravelCar::date]) && is_array($filter[UTTTravelCar::date])) {
                $taxQuery[] = array(
                    'taxonomy' => UTTTravelCar::date,
                    'terms' => $filter[UTTTravelCar::date],
                    'operator' => 'IN'
                );
            }


            if(count($cfQuery) > 0) {
                $cfQuery['ralation'] = 'AND';
            }

            if(count($taxQuery) > 0) {
                $taxQuery['ralation'] = 'AND';
            }


            $query->set('tax_query', $taxQuery);
            $query->set('meta_query', $cfQuery);

        }

        return $query;

    }
}

UTTCarFrontend::init();