<?php

add_filter( 'template_include', 'UTTTraveltemplateCarLoader' );
if(!function_exists('UTTTraveltemplateCarLoader')) {
    function UTTTraveltemplateCarLoader($template)
    {

        if ( is_embed() ) {
            return $template;
        }

        if ( is_singular( UTTTravelCar::postType  ) || is_tax( get_object_taxonomies( UTTTravelCar::postType  )) || is_post_type_archive( UTTTravelCar::postType  ) ) {
            if ( $default_file = UTTTemplateLoad::getTemplateLoader(UTTTravelCar::postType) ) {
                $search_files = UTTTemplateLoad::getTemplateSearch($default_file, UTTConfig::TEMPLATE . '/car/templates/', UTTTravelCar::postType);
                $template     = locate_template( $search_files );
            }

            if (!$template) {
                $template = dirname(UTT_PATH) . '/car/templates/' . $default_file;
            }


        }

        return $template;
    }
}



add_filter('ratings_criteria_car', 'criteriaRatingCar');
if(!function_exists('criteriaRatingCar')) {
    function criteriaRatingCar($default)
    {

        if (get_option('utt_rating_car') == 'on') {
            $utt_rating_criteria = get_option('utt_car_rating_criteria');
            $default = explode(PHP_EOL, $utt_rating_criteria);
            if (count($default) > 0) {
                $_default = [];
                foreach ($default as $item) {
                    $_default[sanitize_title($item)] = $item;
                }

                $default = $_default;
            }
        }

        return $default;
    }
}