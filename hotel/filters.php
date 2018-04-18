<?php

add_filter('utt_post_type_filter', function ($args) {
    $args[UTTTravelHotel::postType] = 'Hotel';
    return $args;
});
add_filter('utt_all_post_type', function ($args) {
    $args[] = UTTTravelHotel::postType;
    return $args;
});

add_filter( 'template_include', 'UTTTraveltemplateHotelLoader' );
if(!function_exists('UTTTraveltemplateHotelLoader')) {
    function UTTTraveltemplateHotelLoader($template)
    {
        if ( is_embed() ) {
            return $template;
        }
        if ( is_singular( UTTTravelHotel::postType  )
            || is_tax( get_object_taxonomies( UTTTravelHotel::postType  ))
            || is_post_type_archive( UTTTravelHotel::postType  ) ) {

            $postType = UTTTravelHotel::postType;
            if (is_tax(UTTTravelTour::regionTour) && get_query_var('post_type')  != $postType) {
                return $template;
            }
            if ( $default_file = UTTTemplateLoad::getTemplateLoader($postType) ) {
                $search_files = UTTTemplateLoad::getTemplateSearch($default_file, UTTConfig::TEMPLATE . '/hotel/templates/', $postType);
                $template     = locate_template( $search_files );
            }

            if (!$template) {
                $template = dirname(UTT_PATH) . '/hotel/templates/' . $default_file;
            }
        }


        return $template;
    }
}

add_filter('ratings_criteria_hotel', 'criteriaRatingHotel');
if(!function_exists('criteriaRatingHotel')) {
    function criteriaRatingHotel($default)
    {
        if (get_option('utt_rating_hotel') == 'on') {
            $utt_rating_criteria = get_option('utt_hotel_rating_criteria');
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