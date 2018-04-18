<?php

add_filter( 'template_include', 'UTTTraveltemplateLoader' );
if(!function_exists('UTTTraveltemplateLoader')) {
    function UTTTraveltemplateLoader($template)
    {
        if ( is_embed() ) {
            return $template;
        }

        if ( is_singular( UTTTravelTour::$postType  )
            || is_tax( get_object_taxonomies( UTTTravelTour::$postType  ))
            || is_post_type_archive( UTTTravelTour::$postType  ) ) {

            $postType = UTTTravelTour::$postType;
            if (is_tax(UTTTravelTour::regionTour) && get_query_var('post_type')  != $postType) {
                return $template;
            }

            if ( $default_file = UTTTemplateLoad::getTemplateLoader(UTTTravelTour::$postType) ) {
                $search_files = UTTTemplateLoad::getTemplateSearch($default_file, UTTConfig::TEMPLATE . '/tour/templates/', UTTTravelTour::$postType);

                $template     = locate_template( $search_files );
            }


            if (!$template) {
                $template = dirname(UTT_PATH) . '/tour/templates/' . $default_file;
            }
        }

        return $template;
    }
}

add_filter('widget_text', 'do_shortcode');

add_filter('utt_post_type_filter', function ($args) {
    $args[UTTTravelTour::$postType] = 'Tour';

    return $args;
});


add_filter('ratings_criteria_tour', 'criteriaRatingTour');
if(!function_exists('criteriaRatingTour')) {
    function criteriaRatingTour($default)
    {

        if (get_option('utt_rating_tour') == 'on') {
            $utt_rating_criteria = get_option('utt_rating_criteria');
            $default = explode(PHP_EOL, $utt_rating_criteria);
            $default = array_filter($default);
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